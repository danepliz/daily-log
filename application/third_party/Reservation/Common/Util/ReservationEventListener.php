<?php
namespace Reservation\Common\Util;

use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;

use Doctrine\Common\EventArgs;

use Doctrine\Common\EventSubscriber;
class ReservationEventListener implements EventSubscriber{

	public function __construct(){

	}

	public function getSubscribedEvents(){
		return array('onFlush');
	}

	public function onFlush(EventArgs $args){

		/* @var $em \Doctrine\ORM\EntityManager
		 *
		* */

//		$em = $args->getEntityManager();
//		$uow = $em->getUnitOfWork();
//
//		$user = NULL;
//
//		if( !  \Current_User::user() )
//		{
//			$customer = \Current_Customer::customer();
//
//			if( $customer )
//			{
//				\CI::$APP->load->helper('web/online');
//				$user = getOnlineAgentUser();
//			}
//		}
//		else
//		{
//			$user = \Current_User::user();
//		}
		

//		foreach ($uow->getScheduledEntityInsertions() AS $entity) {
//
//			if($entity instanceof \models\Transaction\TransactionLog){
//
//				$entity->setIpaddress(\CI::$APP->input->ip_address());
//
//				if($user){
//					$entity->setUsername($user->getUsername());
//					$entity->setUser($user);
//				}
//				$uow->recomputeSingleEntityChangeSet($em->getClassMetaData(get_class($entity)),$entity);
//			}
//
//
//			if($entity instanceof AbstractLogEntry){
//
//				$username = ( $user )? $user->getUsername() : 'Unknown';
//
//				$entity->setUsername($username);
//				$uow->recomputeSingleEntityChangeSet($em->getClassMetaData(get_class($entity)),$entity);
//			}
			
		
// 			if($entity instanceof \models\Commission\CommissionLog){
// 				$entity->setUsername(\Current_User::user()->getUsername());
// 				$uow->recomputeSingleEntityChangeSet($em->getClassMetaData(get_class($entity)),$entity);
// 			}
// 			if($entity instanceof \models\Common\ExchangeRateLog){
// 				$entity->setUsername(\Current_User::user()->getUsername());
// 				$uow->recomputeSingleEntityChangeSet($em->getClassMetaData(get_class($entity)),$entity);
// 			}
// 			if($entity instanceof \models\Agent\AgentLog){
// 				$entity->setUsername(\Current_User::user()->getUsername());
// 				$uow->recomputeSingleEntityChangeSet($em->getClassMetaData(get_class($entity)),$entity);
// 			}
				
//		}
	}
}
