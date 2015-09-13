<?php

namespace Yarsha\YQL;

abstract class YQLFilter{
	public $name;
	
	public function __construct($name){
		$this->name = $name;
	}
	
	abstract public function getSql();
	
	abstract public function parse(\Yarsha\YQL\Parser $parser);

    public function getFilterUI($lable, $element){
     return "<div class='form-group-sm col-md-3'><label>".$lable."</label>".$element."</div>";
    }
}