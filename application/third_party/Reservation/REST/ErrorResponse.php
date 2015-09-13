<?php
namespace Reservation\REST;
use Reservation\REST\Response;

abstract class ErrorResponse implements Response
{
	
	const ERR_UNKNOWN_METHOD = 1000;
	const ERR_INVALID_PARAMETERS = 1001;
	const ERR_NOT_AUTHORISED = 1002;
	const ERR_MTCN_NOT_FOUND = 1003;
	const ERR_VALUE_NOT_MATCHED = 1004;
	const ERR_PAYOUT = 1005;
	const ERR_AML_MATCH_FOUND = 1006;
	
	/* REMIT ERROR CONSTANTS */
	const ERR_INVALID_PAYMENT_TYPE			= 1007;
	const ERR_TXN_LIMIT_EXCEED				= 1008;
	const ERR_DESTINATION_COUNTRY_NOT_FOUND	= 1009;
	const ERR_COMMISSION_SLAB_NOT_FOUND		= 1010;
	const ERR_CREATING_TRANSACTION_FAILED	= 1011;
	const ERR_REMIT_BANK_NOT_FOUND			= 1012;
	const ERR_CTBS_NOT_ENABLED				= 1013;
	const ERR_ID_TYPE_NOT_FOUND				= 1014;
	
	const ERR_BANK_NOT_FOUND				= 1015;
	
	const ERR_TRANSACTION_NOT_FOUND			= 1016;
	const ERR_INVALID_STATUS_REQUEST		= 1017;
	const ERR_UNABLE_TO_UPDATE				= 1018;
	
	const ERR_TRANSACTION_ALREADY_EXISTS	= 1019;
	const ERR_STATE_NOT_FOUND				= 1020;
	
	const ERR_THRESHOLD_EXCEED				= 1021;
	const ERR_CURRENCY_NOT_FOUND			= 1022;
	
	const ERR_AGENT_NOT_FOUND				= 1023;
	
	private $messages = array(
				self::ERR_UNKNOWN_METHOD 		=> 	"Unknown Method",
				self::ERR_INVALID_PARAMETERS	=>	"Invalid Parameters",
				self::ERR_NOT_AUTHORISED		=>	"Authentication Failure",
				self::ERR_MTCN_NOT_FOUND		=>  "MTCN Not Found",
				self::ERR_VALUE_NOT_MATCHED		=> 	"Payout confirmation failed",
				self::ERR_PAYOUT				=> 	"Could Not Make Payout",
				self::ERR_AML_MATCH_FOUND		=> 	"AML Match Found",
				self::ERR_TXN_LIMIT_EXCEED		=>	"Transaction Limit Exceed",
				self::ERR_COMMISSION_SLAB_NOT_FOUND 	=> "Unable to Calculate Service Charge",
				self::ERR_DESTINATION_COUNTRY_NOT_FOUND => "Unauthorized Country",
				self::ERR_INVALID_PAYMENT_TYPE			=> "Invalid Payment Type",
				self::ERR_CREATING_TRANSACTION_FAILED	=> "Unable to Create Transaction",
				self::ERR_REMIT_BANK_NOT_FOUND			=> "Unauthorized Bank",
				self::ERR_CTBS_NOT_ENABLED				=> "Payment Type Disabled",
				self::ERR_ID_TYPE_NOT_FOUND				=> "ID Type Not Found",
				self::ERR_BANK_NOT_FOUND				=> "Bank Not Found",
				self::ERR_TRANSACTION_NOT_FOUND			=> "Transaction Not Found",
				self::ERR_INVALID_STATUS_REQUEST		=> "Invalid Status Request",
				self::ERR_UNABLE_TO_UPDATE				=> "Unable to Complete Request",
				self::ERR_TRANSACTION_ALREADY_EXISTS 	=> "Transaction Already Exists",
				self::ERR_STATE_NOT_FOUND				=> "State Not Found",
				self::ERR_THRESHOLD_EXCEED				=> "Threshold Exceeds",
				self::ERR_CURRENCY_NOT_FOUND			=> "Currency Not Defined",
				self::ERR_AGENT_NOT_FOUND				=> "Agent Code Not Found"
			);
	
	private $errorCode;
	
	private $errorMessage;
	
	private $errorDescription;
	
	private $httpResponseCode;
	
	public function toArray(){
		return array(	'status'			=>	'ERROR',
						'errorCode'			=>	$this->errorCode,
						'errorMessage'		=>	$this->errorMessage,
						'errorDescription'	=>	$this->errorDescription);
	}

	public function getErrorCode()
	{
	    return $this->errorCode;
	}

	public function setErrorCode($errorCode)
	{
	    $this->errorCode = $errorCode;
	    $this->errorMessage = $this->messages[$errorCode];
	}

	public function getErrorMessage()
	{
	    return $this->errorMessage;
	}

	public function setErrorMessage($errorMessage)
	{
	    $this->errorMessage = $errorMessage;
	}

	public function getErrorDescription()
	{
	    return $this->errorDescription;
	}

	public function setErrorDescription($errorDescription)
	{
	    $this->errorDescription = $errorDescription;
	}

	

	public function getHttpResponseCode()
	{
	    return $this->httpResponseCode;
	}

	public function setHttpResponseCode($httpResponseCode)
	{
	    $this->httpResponseCode = $httpResponseCode;
	}
}