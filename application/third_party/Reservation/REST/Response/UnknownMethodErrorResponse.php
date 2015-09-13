<?php

namespace Reservation\REST\Response;
use Reservation\REST\ErrorResponse;


class UnknownMethodErrorResponse extends ErrorResponse
{
	public function __construct(){
		$this->setErrorCode(self::ERR_UNKNOWN_METHOD);
		$this->setHttpResponseCode(HTTP_NOT_FOUND);
	}
}