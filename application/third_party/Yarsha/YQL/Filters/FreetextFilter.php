<?php
namespace Yarsha\YQL\Filters;
use user\models\Group;

use Yarsha\YQL\Lexer;

use Yarsha\YQL\YQLFilter;

class FreetextFilter extends YQLFilter
{
	private $columnName;
	private $filterValue = "''";
	
	public function parse(\Yarsha\YQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
		
	}
	
	public function getSql(){
		
		if (isset($_REQUEST['filter:freetext:' . $this->columnName]) && !empty($_REQUEST['filter:freetext:' . $this->columnName])) {
			
			$request = $_REQUEST['filter:freetext:' . $this->columnName];
			$this->filterValue = $request;
			
			return "like '%". $this->filterValue ."%'";
			
		} 
		
		return "like '%%'";
		
	}
	
public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement(){
        $element= "<input type='text' name='filter:freetext:".$this->columnName."' placeholder='leave empty to match any value' title='leave empty to match any value' class='form-control' value='".$this->filterValue."' />";
        return $this->getFilterUI($this->getFilterLabel(),$element);
//		return "<div class='form-group-sm col-md-3'>
//					<label>".$this->getFilterLabel()."</label>
//					<input type='text' name='filter:freetext:".$this->columnName."' placeholder='leave empty to match any value' title='leave empty to match any value' class='form-control' value='".$this->filterValue."' />
//				</div>";
	}
	
	public function getFilterValue(){
		return '<p class="element"><label>  : '.$this->getFilterLabel().'</label>'.$this->filterValue.'</p>';
	}
}