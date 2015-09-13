<?php
namespace Yarsha\YQL\Filters;
use Yarsha\YQL\Lexer;

use Yarsha\YQL\YQLFilter;

class DateFilter extends YQLFilter
{
	
	private $columnName;
	
	private $date = NULL;
	
	public function parse(\Yarsha\YQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		
		
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
	}
	
	public function getSql(){
		
		$default = (stripos( $this->columnName, 'from') !== FALSE) ? date('Y-m') . '-01' : date('Y-m-d');
		
		if (isset($_REQUEST['filter:date:'.$this->columnName])) {
			$requestDate = $_REQUEST['filter:date:'.$this->columnName];
			if (! isValidDate($requestDate)) $requestDate = NULL;
		}
		
		$date =  isset($requestDate) ? $requestDate : $default;
		$date .= stripos( $this->columnName, 'to') !== FALSE ? ' 23:59:59' : ' 00:00:00';
		
		$this->date = substr($date, 0, 10);
		
		return "'".$date."'";
	}
	
	public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement(){
		return "<div style='float:left; margin-right:10px;'>
					<span>".$this->getFilterLabel()."</span>
					<input type='text' name='filter:date:".$this->columnName."' value='".$this->date."' class='datepicker'title='yyyy-mm-dd format' />
				</div>";
	}
	
	public function getFilterValue(){
		$date = new \DateTime($this->date);
		return '<p class="element"><label>  : '.$this->getFilterLabel().'</label>'.$date->format('F j, Y').'</p>';
	}
}