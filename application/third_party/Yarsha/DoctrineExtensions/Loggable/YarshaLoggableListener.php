<?php

namespace Yarsha\DoctrineExtensions\Loggable;

use Gedmo\Tool\Wrapper\AbstractWrapper;

use Gedmo\Loggable\LoggableListener;

class YarshaLoggableListener extends LoggableListener
{
	protected function createLogEntry($action, $object, $ea){
		$om = $ea->getObjectManager();
		$wrapped = AbstractWrapper::wrap($object, $om);
		$meta = $wrapped->getMetadata();
		if ($config = $this->getConfiguration($om, $meta->name)) {
			
			$logEntryClass = $this->getLogEntryClass($ea, $meta->name);
			$logEntry = new $logEntryClass;
		
			$logEntry->setAction($action);
			$logEntry->setUsername($this->username);
			$logEntry->setObjectClass($meta->name);
			$logEntry->setLoggedAt();
		
			// check for the availability of the primary key
			$objectId = $wrapped->getIdentifier();
			if (!$objectId && $action === self::ACTION_CREATE) {
				$this->pendingLogEntryInserts[spl_object_hash($object)] = $logEntry;
			}
			$uow = $om->getUnitOfWork();
			$logEntry->setObjectId($objectId);
			if ($action !== self::ACTION_REMOVE && isset($config['versioned'])) {
				$newValues = array();
				foreach ($ea->getObjectChangeSet($uow, $object) as $field => $changes) {
					if (!in_array($field, $config['versioned'])) {
						continue;
					}
					$value = $changes[1];
					if ($meta->isSingleValuedAssociation($field) && $value) {
						$oid = spl_object_hash($value);
						$wrappedAssoc = AbstractWrapper::wrap($value, $om);
						$value = $wrappedAssoc->getIdentifier(false);
						if (!is_array($value) && !$value) {
							$this->pendingRelatedObjects[$oid][] = array(
									'log' => $logEntry,
									'field' => $field
							);
						}
					}
					$newValues[$field] = $value;
				}
				$logEntry->setData($newValues);
			}
			$version = 1;
			$logEntryMeta = $om->getClassMetadata($logEntryClass);
			if ($action !== self::ACTION_CREATE) {
				$version = $ea->getNewVersion($logEntryMeta, $object);
				if (empty($version)) {
					// was versioned later
					$version = 1;
				}
			}
			$logEntry->setVersion($version);
			
			
			if( method_exists($object, 'updateVersion')){
				$object->updateVersion();
				$uow->recomputeSingleEntityChangeSet($om->getClassMetaData(get_class($object)),$object);
			}
			
			$this->prePersistLogEntry($logEntry, $object);
		
			$om->persist($logEntry);
			$uow->computeChangeSet($logEntryMeta, $logEntry);
		}
	}
}