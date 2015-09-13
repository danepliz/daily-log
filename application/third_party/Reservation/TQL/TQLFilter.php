<?php

namespace Reservation\TQL;

abstract class TQLFilter{
	public $name;
	
	public function __construct($name){
		$this->name = $name;
	}
	
	abstract public function getSql();
	
	abstract public function parse(\Transborder\TQL\Parser $parser);
}