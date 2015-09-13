<?php

use Yarsha\Common\ActivityManager;

class Yarsha{

	/**
	 *
	 * The instance to Activity Manager
	 * @var $rm Yarsha\Common\ActivityManager
	 */
	public $am;


	public function __construct(){

        $this->am = new ActivityManager();

//		//forex update listener
//		$forexUpdateListener = new Transborder\Forex\ForexEventListener();
//		\CI::$APP->doctrine->em->getEventManager()->addEventSubscriber($forexUpdateListener);
//
//		//agent update listener
//		$agentEventListener = new Transborder\Agent\AgentEventListener();
//		\CI::$APP->doctrine->em->getEventManager()->addEventSubscriber($agentEventListener);
//
//
//		//transborder general event listener
//		$tbeventlistener = new TransborderEventListener();
//		\CI::$APP->doctrine->em->getEventManager()->addEventSubscriber($tbeventlistener);
//
//
//
//		log_message('debug','Transborder Class initialized');
	}
}