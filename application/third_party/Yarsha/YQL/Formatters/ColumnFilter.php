<?php
namespace Yarsha\YQL\Formatters;

class ColumnFilter
{
	const FILTER_TYPE_DATE = 100;
	const FILTER_TYPE_COMBO = 101;
	
	private $column;
	
	private $type;
	
	public function __construct($column,$type){
		$this->column = $column;
		$this->type = $type;
	}
}
