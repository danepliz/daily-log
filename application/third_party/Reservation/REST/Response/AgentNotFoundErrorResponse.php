<?php
namespace Reservation\REST\Response;
use Reservation\REST\ErrorResponse;

class AgentNotFoundErrorResponse extends ErrorResponse
{
	public function __construct(){
		$this->setErrorCode(self::ERR_AGENT_NOT_FOUND);
		$this->setHttpResponseCode(HTTP_BAD_REQUEST);
	}
}