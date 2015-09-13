<?php


class Email_Controller extends MY_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		
		$this->load->config('email', TRUE);
		
		$email_config = $this->config->item('email');
			
		$this->load->library('email');
			
		$this->email->initialize($email_config);
			
		$message = "Test Message 2232323";
			
		$this->email->from($email_config['from_email'], $email_config['from_email_name']);
			
		$this->email->to( 'bhakta@f1soft.com' );
		
		$this->email->cc( 'pradeep@f1soft.com' );
			
		$this->email->subject('FGM Money Transfer Receipt');
			
		$this->email->message($message);
			
		$this->email->send();
	
		echo $this->email->print_debugger();
			
	}
	
	
}