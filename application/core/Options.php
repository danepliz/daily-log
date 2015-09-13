<?php

use models\Option;

class Options{
	private static $options;
	private static $instance;

	
	private function __construct() {}

	public static function __init(){
		if(!isset(self::$options)) {
			
			self::$instance = new Options;
			self::$instance->_loadOptions();
		}
		return;
		//return self::$options;
	}
	
	public static function get($option_name,$default = FALSE)
	{
		if(!isset(self::$options))
			self::__init();
		
		$option_name = trim($option_name);
		
 		if(array_key_exists($option_name,self::$options))
 			return self::$options[$option_name];
		
		//if not autoloaded get it from database
		$CI =& get_instance();
		
		$query = $CI->db->query("SELECT option_value FROM ".CI::$APP->config->item('options_table')." WHERE option_name = '$option_name' LIMIT 1");
		
		if($query->num_rows() == 0)
			return $default;
			
		if(is_object($query->row()))
		{
			$value = $query->row()->option_value;
			return self::$instance->_maybe_unserialize($value);
		}else return $default;
		
	}
	
	public static function set($option, $value = '',$autoload = 1)
	{
		if(!isset(self::$options))
			self::__init();
			
		$option = trim($option);
		
		if ( empty($option) )
	    	return false;
		
		if(self::get($option) === FALSE)
		{
			$value = self::$instance->_maybe_serialize( $value );
			$autoload = ( 0 === $autoload ) ? 0 : 1;	
			
			$CI =& get_instance();
			$query = $CI->db->query("INSERT INTO ".CI::$APP->config->item('options_table')." (`option_name`, `option_value`, `autoload`) VALUES ('$option', '$value', '$autoload') ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)");
			if($query) return TRUE;	
			else return FALSE;
		}else
			show_error('The option <b>"'.$option.'"</b> already exists.');
	}
	
	public static function update($option, $newvalue)
	{
		if(!isset(self::$options))
			self::__init();
		
		$option = trim($option);
		
		if ( empty($option) )
	    	return false;
			
		$oldvalue = self::get( $option );
		
		if ( $newvalue === $oldvalue )
	        return false;
			
		if ( FALSE === $oldvalue )
	        return self::set( $option, $newvalue );
		
		$newvalue = self::$instance->_maybe_serialize( $newvalue );
		
		$CI =& get_instance();
		$CI->db->where('option_name',$option);
		$query = $CI->db->update(CI::$APP->config->item('options_table'),array('option_value'	=>	$newvalue));
		
		if($query){
			//refresh the options
			self::$instance->_loadOptions();
			return TRUE;	
		}
			else return FALSE;
	}
	
	private function _loadOptions()
	{
		$CI =& get_instance();
		$CI->db->select('option_name,option_value');
		$CI->db->where('autoload',1);
		$query = $CI->db->get(CI::$APP->config->item('options_table'));
		$opt = $query->result();
		
		$options = array();
		foreach($opt as $o)
			$options[$o->option_name] = $this->_maybe_unserialize($o->option_value);
		
		self::$options = $options;
		return;
	}
	private function _maybe_serialize( $data ) {
		if ( is_array( $data ) || is_object( $data ) )
	        return serialize( $data );
	
	    if ($this->_is_serialized( $data ) )
	        return serialize( $data );
	    return $data;
	}
	
	private function _maybe_unserialize( $original ) {
	    if ($this->_is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
	            return @unserialize( $original );
	    return $original;
	}
	
	private function _is_serialized( $data ) {
		// if it isn't a string, it isn't serialized
	    if ( ! is_string( $data ) )
	    	return false;
		$data = trim( $data );
	
	    if ( 'N;' == $data )
		    return true;
	    $length = strlen( $data );
	    if ( $length < 4 )
	            return false;
	    if ( ':' !== $data[1] )
	            return false;
	    $lastc = $data[$length-1];
	    if ( ';' !== $lastc && '}' !== $lastc )
	            return false;
	    $token = $data[0];
	    switch ( $token ) {
	            case 's' :
	                    if ( '"' !== $data[$length-2] )
	                            return false;
	            case 'a' :
	            case 'O' :
	                    return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
	            case 'b' :
	            case 'i' :
	            case 'd' :
	                    return (bool) preg_match( "/^{$token}:[0-9.E-]+;\$/", $data );
	    }
	    return false;
	}
	
	
	public function __clone() {
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
}