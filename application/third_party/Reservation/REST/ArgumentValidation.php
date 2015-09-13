<?php

namespace Reservation\REST;

class ArgumentValidation extends \CI_Form_validation
{
	/**
	 * 
	 * @var YSREST_Controller
	 */
	private $rest;
	
	private $_fields;
	
	public function __construct($rest){
		parent::__construct();
		$this->rest = $rest;
		log_message('info','REST argument validation class initialized.');
	}
	
	
	
	function set_rules($field, $rules = '', $label = '')
	{
		// No fields? Nothing to do...
		if ( ! is_string($field) OR  ! is_string($rules) OR $field == '')
		{
			return $this;
		}
	
		// If the field label wasn't passed we use the field name
		$label = ($label == '') ? $field : $label;
	
		// Is the field name an array?  We test for the existence of a bracket "[" in
		// the field name to determine this.  If it is an array, we break it apart
		// into its components so that we can fetch the corresponding POST data later
		if (strpos($field, '[') !== FALSE AND preg_match_all('/\[(.*?)\]/', $field, $matches))
		{
			// Note: Due to a bug in current() that affects some versions
			// of PHP we can not pass function call directly into it
			$x = explode('[', $field);
			$indexes[] = current($x);
	
			for ($i = 0; $i < count($matches['0']); $i++)
			{
			if ($matches['1'][$i] != '')
			{
			$indexes[] = $matches['1'][$i];
			}
			}
	
			$is_array = TRUE;
		}
		else
		{
		$indexes	= array();
		$is_array	= FALSE;
		}
	
		// Build our master array
		$this->_field_data[$field] = array(
				'field'				=> $field,
				'label'				=> $label,
				'rules'				=> $rules,
				'is_array'			=> $is_array,
				'keys'				=> $indexes,
				'postdata'			=> NULL,
				'error'				=> ''
		);
	
		return $this;
	}
	
	/**
	 * 
	 * @param TBREST_Controller $rest
	 */
	function run($group)
	{
		
		$group = $this->rest->$group();

		$this->CI->lang->load('form_validation');
		
		foreach ($this->_field_data as $field => $row)
		{
			if ($row['is_array'] == TRUE)
			{
				$this->_field_data[$field]['postdata'] = $this->_reduce_array($group, $row['keys']);
			}
			else
			{
				if (isset($group[$field]) AND $group[$field] != "")
				{
					$this->_field_data[$field]['postdata'] = $group[$field];
				}
			}
			$this->_execute($row, explode('|', $row['rules']), $this->_field_data[$field]['postdata']);
		}
		
		
		// Did we end up with any errors?
		$total_errors = count($this->_error_array);
// 		
		if ($total_errors > 0)
		{
			$this->_safe_form_data = TRUE;
		}

		// Now we need to re-set the POST data with the new, processed data
		$this->_reset_post_array();

		// No errors, validation passes!
		if ($total_errors == 0)
		{
			return TRUE;
		}

		// Validation fails
		return FALSE;
	}
	
	public function is_money($str){
		return (bool) preg_match('/^[0-9]*\.?[0-9]+$/',$str);
	}
	
	public function money($str){
		return (bool) preg_match('/^[0-9]*\.?[0-9]{1,2}+$/',$str);
	}
	
}