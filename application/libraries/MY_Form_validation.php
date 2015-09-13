<?php


if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation{
    
    function run($module = '', $group = ''){
        (is_object($module)) AND $this->CI = &$module;
            return parent::run($group);
    }

	public function money($str){
		return (bool) preg_match('/^[0-9]*\.?[0-9]{1,2}+$/',$str);
	}
	
	public function forex_rate($str){
		return (bool) preg_match('/^[0-9]*\.?[0-9]{1,4}+$/',$str);
	}
	
	public function phone($str){
		return ( ! preg_match("/^([a-z0-9_-\s@+(),])+$/i", $str)) ? FALSE : TRUE;
	}
	
	function alpha_numeric_tb($str)
	{
		return ( ! preg_match("/^([a-z0-9_\-\s@\'\.])+$/i", $str)) ? FALSE : TRUE;
	}
	
	function valid_username($str)
	{
		return ( ! preg_match("/^([a-z0-9_\-\.])+$/i", $str)) ? FALSE : TRUE;
	}
	
	function decimal($value)
	{
		$CI =& get_instance();
		$CI->form_validation->set_message('decimal',
				'The %s is not a valid decimal number.');
	
		$regx = '/^[-+]?[0-9]*\.?[0-9]*$/';
		if(preg_match($regx, $value))
			return true;
		return false;
	}
	
	function birthdate($value)
	{
		$CI =& get_instance();
		$CI->form_validation->set_message('birthdate', 'The %s must have yyyy-mm-dd format.');
		if ( ! preg_match('/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/', $value) )
		{
			return FALSE;
		}
		else 
		{
			$postedDate = strtotime($value);
			
			$currentDate = date('Y-m-d');
			$toDay = strtotime($currentDate);
			$eighteenYearsBefore = strtotime('-18 years', $toDay);
			
			list($year, $month, $day) = explode("-", $value);
			
			if( $month > 12  or $day > 32) {
				$CI->form_validation->set_message('birthdate', 'The %s is invalid.');
				return FALSE;
			}
			
			if ($eighteenYearsBefore < $postedDate) {
				$CI->form_validation->set_message('birthdate', 'The %s is less than 18 years.');
				return FALSE;
			}
		}
		
		return TRUE;
	}
	
	public function is_unique_phone($str,$cusID = '0'){
		$repo = CI::$APP->doctrine->em->getRepository('\models\Customer');
		try{
			$phone = $repo->findBy(array('phone'=>$str));
			
			//customer edit
			if($cusID != '0'){
				$cRepo = CI::$APP->doctrine->em->find('models\Customer',$cusID);
				$num = $cRepo->getPhone();
				if($num == $str) return true;		
			}
			if(count($phone)>0 ){
				throw new Exception("Phone Number Already Exist");
			}
		}
		catch (Exception $ex){
			CI::$APP->form_validation->set_message('is_unique_phone',$ex->getMessage());
			return FALSE;
		}
		
		return TRUE;
	}
	
	public function phone_fgm($str){
		if($str == ''){
			return true;
		}
		elseif( ! preg_match("/\(\d{3}\)\d{3}-\d{3,4}\$/", $str)){
			$CI =& get_instance();
			$CI->form_validation->set_message('phone_fgm','Phone Number must be entered in (000)000-0000 format.');
			return False;
		}
		else{
			return true;
		}
	}
	
	public function house_fgm($str){
		$CI = & get_instance();
		if(! preg_match("/[A-Za-z0-9\-\/]\$/", $str)){
					$CI->form_validation->set_message('house_fgm','House number only contails alpha numeric and \- characters');
					return false;
			}
			else{
				return true;
			}
	}
	
	public function apartment_fgm($str){
		$CI = & get_instance();
		if(! preg_match("/[A-Za-z0-9\-\/]\$/", $str)){
			$CI->form_validation->set_message('apartment_fgm','Apartment number only contails alpha numeric and \- characters');
			return false;
		}
		else{
			return true;
		}
	}

    function ys_date($value)
    {
        $CI =& get_instance();
        if ( ! preg_match('/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/', $value) )
        {
            $CI->form_validation->set_message('ys_date', 'The %s must have yyyy-mm-dd format.');
            return FALSE;
        }else{
            return TRUE;
        }
    }

    function date_compare($end_date, $compare_key) {

        $CI =& get_instance();

        $start_date = $CI->input->post($compare_key);

        if ( ! preg_match('/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/', $start_date) or ! preg_match('/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/', $end_date))
        {
            $CI->form_validation->set_message('date_compare', 'The %s must have yyyy-mm-dd format.');
            return FALSE;
        }else{
            $from_date = new DateTime($start_date);
            $to_date = new DateTime($end_date);

            if( $from_date >= $to_date ){
                $CI->form_validation->set_message('date_compare', 'To date must be greater than From date');
                return false;
            }else{
                return true;
            }
        }

    }
} 