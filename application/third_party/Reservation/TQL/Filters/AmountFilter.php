<?php
namespace Reservation\TQL\Filters;

use Reservation\TQL\Lexer;

use Reservation\TQL\TQLFilter;

class AmountFilter extends TQLFilter
{
	
	private $columnName;
	private $columnDesc;
	private $filterValue = 0;
	private $comparingOperator = 'gt';
	private $operator = '>';
	private $operatorDesc = 'Greater Than';
	
	public function parse(\Reservation\TQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
		
		$filterLabel = explode(' ', $this->getFilterLabel());
		
		if(count($filterLabel)>1)
			$this->columnDesc = ucfirst(strtolower($filterLabel[0]));
		
		$this->comparingOperator = strtolower($filterLabel[count($filterLabel)-1]);
		
		switch(strtolower($this->comparingOperator))
		{
			case 'lt'	: $this->operator = '<';  	$this->operatorDesc = 'Less Than'; 				break;
			case 'lte' 	: $this->operator = '<='; 	$this->operatorDesc = 'Less Than or Equals To'; break;
			case 'gt' 	: $this->operator = '>'; 	$this->operatorDesc = 'Greater Than'; 			break;
			case 'gte' 	: $this->operator = '>='; 	$this->operatorDesc = 'Greater Than or Equals To'; break;
			case 'eq' 	: $this->operator = '='; 	$this->operatorDesc = 'Equals To'; 				break;
			default 	: $this->operator = '>'; 	$this->operatorDesc = 'Greater Than'; 			break;
		}
		
	}
	
	public function getSql(){
		
		$return = " IS NOT NULL ";
		
		if (isset($_REQUEST['filter:amount:' . $this->columnName]) && !empty($_REQUEST['filter:amount:' . $this->columnName])) {
			
			$request = $_REQUEST['filter:amount:' . $this->columnName];
			$this->filterValue = $request;
			
			$return = " ".$this->operator." ".$this->filterValue." ";
		}
		return $return;
		
	}
	
	public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement(){
		return "<div style='float:left; margin-right:10px;'>
					<span>".$this->columnDesc." ".$this->operatorDesc."</span>
					<input type='text' name='filter:amount:".$this->columnName."' placeholder='' title='' value='".$this->filterValue."' class='number' />
				</div>";
	}
	
	public function getFilterValue(){
		return '<p class="element"><label>  : '.$this->columnDesc.' ('.$this->operator.') </label>'.$this->filterValue.'</p>';
	}
}