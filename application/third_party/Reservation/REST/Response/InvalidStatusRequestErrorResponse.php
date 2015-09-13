<?php
namespace Reservation\REST\Response;
use Reservation\REST\ErrorResponse;

class InvalidStatusRequestErrorResponse extends ErrorResponse
{
	public function __construct(){
		$this->setErrorCode(self::ERR_INVALID_STATUS_REQUEST);
		$this->setHttpResponseCode(HTTP_BAD_REQUEST);
	}
}