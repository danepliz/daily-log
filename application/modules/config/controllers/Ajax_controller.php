<?php
class Ajax_Controller extends Xhr
{
	public function __construct(){
		parent::__construct();
	}

	public function changeLanguage($language)
	{
		$this->load->library('session');
		
		$this->session->set_userdata('language', $language);
	}

	function getCurrencySelectElement($name, $selected = NULL, $attributes = NULL){
		$CI = CI::$APP;
		$currencyRepo = $CI->doctrine->em->getRepository('currency\models\Currency');
		$currencies = $currencyRepo->findBy(
			[], //condition
			array('name' =>'ASC')
		);
		$options = array('' => '-- SELECT CURRENCY --');
		if( count($currencies) > 0 ){
			foreach($currencies as $currency){
				$options[$currency->id()] = $currency->getIso3();
			}
		}
		echo form_dropdown($name,$options,$selected,$attributes);
	}
}