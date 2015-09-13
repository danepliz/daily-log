<?php

class MY_Log extends CI_Log{
	
	protected $_levels	= array('ERROR' => '1', 'INFO' => '2',  'DEBUG' => '3', 'ALL' => '4');
	
	public function __construct(){
		$config =& get_config();

		$this->_log_path = ($config['log_path'] != '') ? $config['log_path'] : APPPATH.'logs/';

		if ( ! is_dir($this->_log_path) OR ! is_really_writable($this->_log_path))
		{
			$this->_enabled = FALSE;
		}

		if (is_numeric($config['log_threshold']))
		{
			$this->_threshold = $config['log_threshold'];
		}

		if ($config['log_date_format'] != '')
		{
			$this->_date_fmt = $config['log_date_format'];
		}
	}
	
	
	public function write_log($level = 'error', $msg, $php_error = FALSE, $exception = NULL)
	{
		
		
		if ($this->_enabled === FALSE)
		{
			return FALSE;
		}
	
		$level = strtoupper($level);
	
		if ( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
		{
			return FALSE;
		}
	
		$filepath = $this->_log_path.'log-'.date('Y-m-d').EXT;
		$message  = '';
	
		if ( ! file_exists($filepath))
		{
			$message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
		}
	
		if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE))
		{
			return FALSE;
		}
	
		if(class_exists('Current_User') && Current_User::user()){
			
			CI::$APP->load->library('session');

			if (is_numeric($main_user = CI::$APP->session->userdata('main_user')) and $main_user > 0)
				$main_user = CI::$APP->doctrine->em->find('user\models\User', $main_user);
			
			if ($main_user and !$main_user->isDeleted() and $main_user->isActive() 
				and Current_User::user()->getEmail() != $main_user->getEmail()) {
					
				$message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($this->_date_fmt). ' ['.CI::$APP->input->ip_address().'] ['. $main_user->getEmail() .' as ' . Current_User::user()->getEmail() . '] '.' --> '.$msg."\n";
			
			} else 
				$message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($this->_date_fmt). ' ['.CI::$APP->input->ip_address().'] ['.Current_User::user()->getEmail().'] '.' --> '.$msg."\n";
	
		} else
			$message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($this->_date_fmt). '[Not Logged In] '.' --> '.$msg."\n";
	
		flock($fp, LOCK_EX);
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);
		
		@chmod($filepath, FILE_WRITE_MODE);
		
		if($level == 'error'){
			CI::$APP->load->library('email');
			
			CI::$APP->email->from('noreply@yarshastudio.com.com', 'Yarsha Studio');
			
			$to = CI::$APP->config->item('error_email') ? CI::$APP->config->item('error_email'):'bhattabhakta@yarshastudio.com';
			
			CI::$APP->email->to($to);
			if(CI::$APP->config->item('error_email_cc')){
				CI::$APP->email->cc(CI::$APP->config->item('error_email_cc'));
			}
			
			CI::$APP->email->subject('An Error Occurred');
			
			$data = array(	'template'	=>	'error_email',
							'msg'		=>	$msg,
			);
			
			$message = CI::$APP->load->theme('emails/master', $data, TRUE);
			
			CI::$APP->email->message($message);
			CI::$APP->email->send();
		}
		return TRUE;
	}
}