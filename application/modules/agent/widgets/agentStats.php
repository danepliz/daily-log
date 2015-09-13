<?php

class agentStats extends Widget{
	
	function run(){
	
		$ci = & \CI::$APP;
		
		$agent = Current_User::user()->getAgent();
		
		if($agent->getParentAgent() !== NULL && user_access('view agent exposure'))
		{
			$trepo = $ci->doctrine->em->getRepository('models\Agent');
			$txnCounts = $trepo->getAgentTransactionCount($agent->id());
			
			try {
				$totalPayments = $trepo->getAgentsTotalPayment($agent->getAccount()->id());
				$data['agent_payment'] = $totalPayments;
				$data['credit_payment '] = $trepo->getAgentPaymentFgm($agent->getAccount()->id());
			} catch (\Exception $e) {
				$data['agent_payment'] = NULL;
				$data['credit_payment'] = NULL;
			}
			
			
			$data['agent'] = &$agent; 
			$data['txnCounts'] = $txnCounts;
			
				
			$this->render('agentstats', $data);
		}
	}
	
}