<?php

namespace Reservation\REST\Response;
use Reservation\REST\ErrorResponse;

class NotAuthorisedErrorResponse extends ErrorResponse
{
	public function __construct(){
		$this->setErrorCode(self::ERR_NOT_AUTHORISED);
		$this->setHttpResponseCode(HTTP_NOT_AUTHORIZED);
	}
}