<?php
namespace Reservation\REST\Response;
use Reservation\REST\ErrorResponse;

class IDTypeNotFoundErrorResponse extends ErrorResponse
{
	public function __construct(){
		$this->setErrorCode(self::ERR_ID_TYPE_NOT_FOUND);
		$this->setHttpResponseCode(HTTP_BAD_REQUEST);
	}
}