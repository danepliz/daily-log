<?php
namespace Yarsha\DoctrineExtensions\EntityAudit;

use SimpleThings\EntityAudit\AuditConfiguration;

class YarshaAuditConfiguration extends AuditConfiguration
{
	private $username;
	
	public function __construct(){
		$this->username = function(){ return '';};
	}
	
	public function setCurrentUsername(\Closure $p){
		$this->username = $p;
	}
	
	public function getCurrentUsername(){
		$p = $this->username;
		return $p();
	}
}