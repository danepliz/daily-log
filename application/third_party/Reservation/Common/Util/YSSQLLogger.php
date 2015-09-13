<?php
namespace Reservation\Common\Util;

use Doctrine\DBAL\Logging\SQLLogger;

class YSSQLLogger implements SQLLogger{
	
	public $start = null;
	
	private $_log_path;
	
	public function __construct(){
		$this->_log_path = APPPATH.'logs/SQLLogs/';
	}
	/**
	 * {@inheritdoc}
	 */
	public function startQuery($sql, array $params = null, array $types = null)
	{
		
		$this->start = microtime(true);
		\CI::$APP->db->queries[] = "/* doctrine */ \n".$sql;
		
		if(strstr($sql, 'SELECT'))
			return TRUE;
			
		$filepath = $this->_log_path.'SQL_log-'.date('Y-m-d').EXT;
		$message  = date('Y-m-d H:i:s').' ---> '.$sql;
		
		if(!is_null($params))
			$message .= " \t ".json_encode($params).PHP_EOL;
		
		if ( ! file_exists($filepath))
		{
			$message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
		}
		
		if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE))
		{
			return FALSE;
		}
		
		flock($fp, LOCK_EX);
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);
	
		@chmod($filepath, FILE_WRITE_MODE);
		return TRUE;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function stopQuery()
	{
		\CI::$APP->db->query_times[] = microtime(true) - $this->start;
	}
}