<?php

namespace Yarsha\YQL\Formatters;

class AggregateField
{
	const TYPE_SUM = 100;
	const TYPE_AVERAGE = 101;
	
	private $field ;
	
	private $type;
	
	private $values = array();
	
	public function __construct($field, $type){
		$this->field = $field;
		$this->type = $type;
	}
	
	public function getField(){
		return $this->field;
	}
	
	public function processRow($row, &$table){
		foreach($row as $k => $v){
			if($k == $this->field){
				$this->values[] = $v;
				
				if(!isset($table['aggregates'][$this->field][$this->getTypeString()])){
					$table['aggregates'][$this->field][$this->getTypeString()] = $v;
				}else{
					if($this->type == self::TYPE_SUM){
						$table['aggregates'][$this->field]['SUM'] += $v; 
					}else if($this->type == self::TYPE_AVERAGE){
						$txnCnt = count($table['transactions']);
						$table['aggregates'][$this->field]['AVERAGE'] = 
						($v/$txnCnt)+($table['aggregates'][$this->field]['AVERAGE'])*(($txnCnt-1)/$txnCnt);
					}
				}
				
			}
		}
	}
	
	public function getTypeString(){
		if($this->type == self::TYPE_SUM){
			return 'SUM';
		}else if($this->type == self::TYPE_AVERAGE){
			return 'AVERAGE';
		}
	}
	
	public function getResult(){
		if($this->type == self::TYPE_SUM){
			return array_sum($this->values);
		}else if($this->type == self::TYPE_AVERAGE){
			return (array_sum($this->values)/count($this->values));
		}
	}
}