<?php
class MY_Exceptions extends CI_Exceptions
{
	function show_php_error($severity, $message, $filepath, $line)
	{
		$severity = ( ! isset($this->levels[$severity])) ? $severity : $this->levels[$severity];
	
		$filepath = str_replace("\\", "/", $filepath);
	
		// For safety reasons we do not show the full file path
		if (FALSE !== strpos($filepath, '/'))
		{
			$x = explode('/', $filepath);
			$filepath = $x[count($x)-2].'/'.end($x);
		}
	
		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		
		if(ENVIRONMENT == 'production'){
			$template = APPPATH.'errors/error_php'.EXT;
		}else{
			$template = APPPATH.'errors/error_dev_php'.EXT;
		}
		
		ob_start();
		include($template);
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}
	
	/**
	 * 404 Page Not Found Handler
	 *
	 * @access	private
	 * @param	string
	 * @return	string
	 */
	function show_404($page = '', $log_error = TRUE)
	{
		$error_template = ( !\Current_User::user() )? 'error_front' : 'error_404';
	
		$heading = "404 Page Not Found";
		$message = "The page you requested was not found.";
		
		// By default we log this, but allow a dev to skip it
		if ($log_error)
		{
			log_message('error', '404 Page Not Found --> '.$page);
		}
		
		echo $this->show_error($heading, $message, $error_template, 404);
		
		exit;
	}
}