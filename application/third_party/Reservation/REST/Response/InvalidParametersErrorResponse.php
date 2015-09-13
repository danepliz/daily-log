<?php
namespace Reservation\REST\Response;
use Reservation\REST\ErrorResponse;

class InvalidParametersErrorResponse extends ErrorResponse
{
	public function __construct(){
		$this->setErrorCode(self::ERR_INVALID_PARAMETERS);
		$this->setHttpResponseCode(HTTP_BAD_REQUEST);
	}
}