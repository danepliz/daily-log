<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Xhr extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		if($this->input->is_ajax_request() == FALSE)
			die('Cannot access this page directly.');
	}
	
}