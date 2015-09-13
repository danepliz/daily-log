<?php

use Reservation\REST\ThirdPartyRemitConstants;

use Reservation\REST\Response\InvalidParametersErrorResponse;

use Reservation\REST\Response\NotAuthorisedErrorResponse;

use Reservation\REST\ArgumentValidation;

use Doctrine\Common\Annotations\DocParser;

use Doctrine\Common\Annotations\PhpParser;

class YSREST_Controller extends REST_Controller{
	
	protected $user;
	
	protected $application;
	
	private $token;
	
	private $messages;
	
	var $called_resource;
	
	private $knownArguments = array();
	
	public function __construct(){
		//$this->messages = $this->load->config('restmessages');
		
		$controller = $this->router->class;
		
		$this->called_resource = ucfirst(str_replace($this->config->item('controller_suffix'), '', $controller));
		parent::__construct();
		
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see REST_Controller::early_checks()
	 */
	protected function early_checks()
	{
		$method = $this->request->method;
		
		$username = $this->$method('apiusername');
		$password = $this->$method('apipwd');
		$appkey = $this->$method('appkey');
		
		//validate fields
		if(!$username || !$password || !$appkey)
		{
			$response = new NotAuthorisedErrorResponse();
			$response->	setErrorDescription('Access Denied.');
			$this->response($response->toArray(),$response->getHttpResponseCode());
		}
		else
		{
			$cr = $this->doctrine->em->getRepository('user\models\User');
			$u = $cr->findOneBy(array('username' => $username));
			
			//validate username password here
			if(!$u){
				$response = new NotAuthorisedErrorResponse();
				$response->	setErrorDescription('Authentication Failed');
				$this->response($response->toArray(),$response->getHttpResponseCode());
			}
			
			if( $u->getPassword() != md5($this->$method('apipwd'))){
				$response = new NotAuthorisedErrorResponse();
				$response->	setErrorDescription('Authentication Failed');
				$this->response($response->toArray(),$response->getHttpResponseCode());
			}
			
			$agent = $u->getAgent();
			$pagent = (!$agent->getParentAgent())? $agent : $agent->getParentAgent();
			
			$apprepo = $this->doctrine->em->getRepository('models\Application');
			$app = $apprepo->findOneBy(array('app_key' => $appkey, 'agent' => $pagent, 'status' => 1));

			//validate application
			if(!$app)
			{
				$response = new NotAuthorisedErrorResponse();
				$response->setErrorDescription('Authentication Failed');
				$this->response($response->toArray(), $response->getHttpResponseCode());
			}
			
			Current_User::setUser($u);
			
			$this->application = $app;
			$this->user = $u;
		}
		
		$this->_validateArguments();
	}
	
	
	protected function _validateArguments(){
		$requested_class = $this->router->class;
		$requested_class_method = $this->router->method;
		$requested_http_method = $this->request->method;
		
		$requested_api_method = $this->router->method.'_'.$this->request->method;
		
		if(method_exists($requested_class, $requested_api_method)){
			
			$method	= new ReflectionMethod($requested_class,$requested_api_method);
			$reader = new \Doctrine\Common\Annotations\AnnotationReader();
			$methodAnnotations = $reader->getMethodAnnotations($method);
	
			if(count($methodAnnotations) > 0){
				$validator = new ArgumentValidation($this);
				
				foreach($methodAnnotations as $args){
					$this->knownArguments[] = $args->name;
					$validator->set_rules($args->name,$args->rules);
				}
				
				
				$this->_checkUnwantedParameters();
				
				$valid = $validator->run($requested_http_method);
				
				if(!$valid){
					$errorDescription = array();
					
					foreach($validator->_error_array as $e){
						$errorDescription[] = $e;
					}
					
					$response = new InvalidParametersErrorResponse();
					$response->setErrorDescription($errorDescription);
					$this->response($response->toArray(),$response->getHttpResponseCode());
				}
			}
		}
	}	
	
	private function _checkUnwantedParameters(){
		//check for unknown parameters
		$requested_http_method = $this->request->method;
		$inputParameters = $this->$requested_http_method();
			
		$unwantedParameters = array();
			
		foreach($inputParameters as $k => $v){
			if($k !== 'apiusername' && $k !== 'apipwd' && $k !== 'appkey'){
				if(!in_array($k, $this->knownArguments)){
					$unwantedParameters[] = $k;
				}
			}
		}
			
		if(count($unwantedParameters) > 0){
			$this->lang->load('form_validation');
			//$errorLine = $this->lang->line('unknown_parameter');
			$errorLine = "Unwanted Parameter %s";
			$error_messages = array();
			
			foreach($unwantedParameters as $u){
				$error_messages[] = sprintf($errorLine,$u);
			}
			
			$response = new InvalidParametersErrorResponse();
			$response->setErrorDescription($error_messages);
			
			$this->response($response->toArray(),$response->getHttpResponseCode());
		}
	}
	
	public function getPaymentTypeForCommissionCalculation($type)
	{
		switch(strtoupper($type))
		{
//			case ThirdPartyRemitConstants::PAYMENT_TYPE_ID 		: return Transaction::TYPE_ID_PAYMENT; break;
//			case ThirdPartyRemitConstants::PAYMENT_TYPE_BANK	: return Transaction::TYPE_BANK_PAYMENT; break;
//			case ThirdPartyRemitConstants::PAYMENT_TYPE_HOME_DELIVERY	: return Transaction::TYPE_ID_PAYMENT; break;
// 			case ThirdPartyRemitConstants::PAYMENT_TYPE_WALLET	: return Transaction::TYPE_ID_PAYMENT; break;
// 			case ThirdPartyRemitConstants::PAYMENT_TYPE_MAWALLET: return Transaction::TYPE_ID_PAYMENT; break;
// 			case ThirdPartyRemitConstants::PAYMENT_TYPE_EWALLET	: return Transaction::TYPE_ID_PAYMENT; break;
			default: return null; break;
		}
	}
	
	public function getPaymentType($type)
	{
		switch(strtoupper($type))
		{
//			case ThirdPartyRemitConstants::PAYMENT_TYPE_ID 		: return Transaction::TYPE_ID_PAYMENT; break;
//			case ThirdPartyRemitConstants::PAYMENT_TYPE_BANK	: return Transaction::TYPE_BANK_PAYMENT; break;
//			case ThirdPartyRemitConstants::PAYMENT_TYPE_HOME_DELIVERY	: return Transaction::TYPE_HOME_DELIVERY; break;
// 			case ThirdPartyRemitConstants::PAYMENT_TYPE_WALLET	: return Transaction::TYPE_WALLET_PAYMENT; break;
// 			case ThirdPartyRemitConstants::PAYMENT_TYPE_MWALLET	: return Transaction::TYPE_WALLET_PAYMENT; break;
// 			case ThirdPartyRemitConstants::PAYMENT_TYPE_EWALLET	: return Transaction::TYPE_WALLET_PAYMENT; break;
			default: return null; break;
		}
	}
	
	public function getCountry($countryCode, $remitCountries = NULL, $errorMessage)
	{
		$countryRepo = $this->doctrine->em->getRepository('models\Common\Country');
		
		$country = $countryRepo->findOneBy(array('iso_3' => $countryCode));
		
		$operatingCountries = ($remitCountries and is_array($remitCountries))? $remitCountries : Options::get('config_operating_countries');
		
		if(!$country or !in_array($country->id(), $operatingCountries))
		{
			$response = new RemitDestinationCountryErrorResponse();
			$response->setErrorDescription($errorMessage);
			$this->response($response->toArray(), $response->getHttpResponseCode());
		}
		
		return $country;
	}
	
	public function getBankBranch($branchId, $destinationCountry = NULL, $errormessage)
	{
		$country_id = ($destinationCountry)? $destinationCountry->id() : 0;
		
		$bank = $this->doctrine->em->find('models\Common\BankBranch', $branchId);
		if(!$bank or $country_id != $bank->getBank()->getCity()->getCountry()->id())
		{
			$response = new RemitBankErrorResponse();
			$response->setErrorDescription($errormessage);
			$this->response($response->toArray(), $response->getHttpResponseCode());
		}
		return $bank;
	}

	public function calculateCommission($sendingAmount, $sourceCurrency, $targetCurrency, $remittingAgent, $destinationCountry, $payoutAgent, $paymentType, $bank_id)
	{
		$serviceChargeCalculation 	= new CommissionCalculator();
	
		try{
			$calculatedCommission 	= $serviceChargeCalculation->calculateTotalAmount($sendingAmount, $sourceCurrency, $targetCurrency, $remittingAgent, $destinationCountry, $payoutAgent, $paymentType, $bank_id);
				
			return $calculatedCommission;
		}
		catch(\Exception $e)
		{
			$response = new CommissionStructureErrorResponse();
			$response->setErrorDescription($e->getMessage());
			$this->response($response->toArray(), $response->getHttpResponseCode());
		}
	
	}

}