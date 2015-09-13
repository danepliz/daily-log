<?php
namespace Reservation\REST\Response;
use Reservation\REST\ErrorResponse;

class StateNotFoundErrorResponse extends ErrorResponse
{
	public function __construct(){
		$this->setErrorCode(self::ERR_STATE_NOT_FOUND);
		$this->setHttpResponseCode(HTTP_BAD_REQUEST);
	}
}