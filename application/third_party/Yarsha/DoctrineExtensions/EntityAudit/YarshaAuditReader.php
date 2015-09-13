<?php

namespace Yarsha\DoctrineExtensions\EntityAudit;
use SimpleThings\EntityAudit\AuditReader;

class YarshaAuditReader extends AuditReader{
	
	public function getCurrentRevision( $className, $id ){
		if (!$this->metadataFactory->isAudited($className)) {
			throw AuditException::notAudited($className);
		}
		
		$class = $this->em->getClassMetadata($className);
		$tableName = $this->config->getTablePrefix() . $class->table['name'] . $this->config->getTableSuffix();
		
		if (!is_array($id)) {
			$id = array($class->identifier[0] => $id);
		}
		
		$whereSQL = "";
		foreach ($class->identifier AS $idField) {
			if (isset($class->fieldMappings[$idField])) {
				if ($whereSQL) {
					$whereSQL .= " AND ";
				}
				$whereSQL .= "e." . $class->fieldMappings[$idField]['columnName'] . " = ?";
			} else if (isset($class->associationMappings[$idField])) {
				if ($whereSQL) {
					$whereSQL .= " AND ";
				}
				$whereSQL .= "e." . $class->associationMappings[$idField]['joinColumns'][0] . " = ?";
			}
		}
		
		$query = "SELECT r.rev FROM " . $this->config->getRevisionTableName() . " r " .
				"INNER JOIN " . $tableName . " e ON r.id = e." . $this->config->getRevisionFieldName() . " WHERE " . $whereSQL . " ORDER BY r.id DESC";
		$revision = $this->em->getConnection()->fetchColumn($query, array_values($id));
		
		return $revision;
		
		$revisions = array();
		$this->platform = $this->em->getConnection()->getDatabasePlatform();
		foreach ($revisionsData AS $row) {
			$revisions[] = new Revision(
					$row['id'],
					\DateTime::createFromFormat($this->platform->getDateTimeFormatString(), $row['timestamp']),
					$row['username']
			);
		}
		
		return $revisions;
	} 
}