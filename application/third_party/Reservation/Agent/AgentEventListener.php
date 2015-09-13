<?php
namespace Yarsha\Agent;


use agent\models\Agent;

use Doctrine\Common\EventArgs;

use Doctrine\Common\EventSubscriber;


class AgentEventListener implements EventSubscriber{
	
	public function __construct(){
	
	}
	
	public function getSubscribedEvents(){
		return array('onFlush');
	}
	
	public function onFlush(EventArgs $args){
		
		/* @var $em \Doctrine\ORM\EntityManager
		 *
		* */

		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork();
		
		foreach ($uow->getScheduledEntityInsertions() AS $entity) {
			if($entity instanceof Agent){
				if($entity->getParentAgent() === NULL){
				
				}
			}
		}			
	}
}