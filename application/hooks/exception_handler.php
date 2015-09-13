<?php



function tb_exception_handler(\Exception $e){
	
	$_error =& load_class('Exceptions', 'core');
	
	$_error->show_php_error('exception', $e->getMessage(), $e->getFile(), $e->getLine());
	
	// Should we log the error?  No?  We're done...
	if (config_item('log_threshold') == 0)
	{
		return;
	}
	
	$_error->log_exception('exception', $e->getMessage(), $e->getFile(), $e->getLine(), $e);
	
	
	CI::$APP->load->library('email');
		
	CI::$APP->email->from('noreply@yarshastudio.com', 'Bus');
		
	$to = CI::$APP->config->item('error_email') ? CI::$APP->config->item('error_email'):'bhattabhakta@yarshastudio.com';
		
	CI::$APP->email->to($to);
	
	$tocc = CI::$APP->config->item('error_email_cc') ? CI::$APP->config->item('error_email_cc') : 'pradeep.karki@yarshastudio.com';
	
	CI::$APP->email->cc($tocc);
	
// 	if(CI::$APP->config->item('error_email_cc')){
// 		CI::$APP->email->cc(CI::$APP->config->item('error_email_cc'));
// 	}
		
	CI::$APP->email->subject('An Exception Occurred');
		
	$data = array(	'template'	=>	'error_email_exception',
					'e'			=>	$e,
	);
	
	$message = CI::$APP->load->theme('emails/master', $data, TRUE);
		
	CI::$APP->email->message($message);
	CI::$APP->email->send();
	
}

function tb_shutDown(){
	$errfile = "unknown file";
	$errstr  = "shutdown";
	$errno   = E_CORE_ERROR;
	$errline = 0;
	
	$error = error_get_last();
	
	if( $error !== NULL) {
		$errno   = $error["type"];
		$errfile = $error["file"];
		$errline = $error["line"];
		$errstr  = $error["message"];
		
		$_error =& load_class('Exceptions', 'core');
		
		$_error->show_php_error('error', $errstr, $errfile, $errline);
		
		// Should we log the error?  No?  We're done...
		if (config_item('log_threshold') == 0)
		{
			return;
		}
		
		$_error->log_exception('exception', $errstr, $errfile, $errline);
		
		CI::$APP->load->library('email');
		
		CI::$APP->email->from('noreply@f1soft.com', 'First Global Money');
		
		$to = CI::$APP->config->item('error_email') ? CI::$APP->config->item('error_email'):'bhattabhakta@yarshastudio.com';
		
		CI::$APP->email->to($to);
		
		$tocc = CI::$APP->config->item('error_email_cc') ? CI::$APP->config->item('error_email_cc') : 'pradeep.karki@yarshastudio.com';
		
		CI::$APP->email->cc($tocc);
		
// 		if(CI::$APP->config->item('error_email_cc')){
// 			CI::$APP->email->cc(CI::$APP->config->item('error_email_cc'));
// 		}
		
		CI::$APP->email->subject('An Exception Occurred');
		
		$data = array(	'template'	=>	'error_email',
						'msg'		=>	$msg,
			);
			
		$message = CI::$APP->load->theme('emails/master', $data, TRUE);
		
		CI::$APP->email->message($message);
		CI::$APP->email->send();
	}
}

function tb_set_exception_handler(){
	set_exception_handler('tb_exception_handler');
// 	register_shutdown_function('tb_shutDown');
}