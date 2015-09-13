<?php
use models\User;
use models\User\Group;
use models\Common\Currency;

class amountCalculation extends Widget{
	
	function run(){
	
		$ci = & \CI::$APP;
		$ci->load->helper('country/country');
		
		$agent = Current_User::user()->getAgent();
		$batches = $agent->getForexBatch();
		$countries = getDestinationCountries();
		
		$applicablePayoutCurrencies = array();
		
		$currencyRepo = $ci->doctrine->em->getRepository('models\Common\Currency');
		$usd = $currencyRepo->findOneBy(array('iso_code' => 'USD'));
		$applicablePayoutCurrencies[$usd->id()] = $usd->getIsoCode().'-'.$usd->getName();
		
		$receivingCurrency = $agent->getCountry()->getCurrency();
		
		$applicablePayoutCurrencies[$receivingCurrency->id()] = $receivingCurrency->getIsoCode().'-'.$receivingCurrency->getName();
		
		if($agent->getParentAgent() !== NULL)
		{
			$data['countries'] = $countries;
			$data['forexBatches'] = &$batches;
			$data['payoutCurrencies'] = &$applicablePayoutCurrencies;
			$this->render('amountcalculation', $data);
		}
	}
	
}