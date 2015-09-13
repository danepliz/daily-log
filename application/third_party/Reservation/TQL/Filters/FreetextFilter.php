<?php
namespace Reservation\TQL\Filters;
use user\models\Group;

use Reservation\TQL\Lexer;

use Reservation\TQL\TQLFilter;

class FreetextFilter extends TQLFilter
{
	
	private $columnName;
	private $filterValue = "''";
	
	public function parse(\Reservation\TQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
		
	}
	
	public function getSql(){
		
		if (isset($_REQUEST['filter:freetext:' . $this->columnName]) && !empty($_REQUEST['filter:freetext:' . $this->columnName])) {
			
			$request = $_REQUEST['filter:freetext:' . $this->columnName];
			$this->filterValue = $request;
			
			return "= '". $this->filterValue ."'";
			
		} 
		
		return "like '%%'";
		
	}
	
public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement(){
		return "<div style='float:left; margin-right:10px;'>
					<span>".$this->getFilterLabel()."</span>
					<input type='text' name='filter:freetext:".$this->columnName."' placeholder='leave empty to match any value' title='leave empty to match any value' value='".$this->filterValue."' />
				</div>";
	}
	
	public function getFilterValue(){
		return '<p class="element"><label>  : '.$this->getFilterLabel().'</label>'.$this->filterValue.'</p>';
	}
}