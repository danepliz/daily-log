<?php
namespace Yarsha\YQL\Filters;

//use models\Transaction;

use Yarsha\YQL\Lexer;

use Yarsha\YQL\YQLFilter;

class TransactionStatusFilter extends YQLFilter
{
	
	private $columnName;
	
	private $status = NULL;
	
	public function parse(\Yarsha\YQL\Parser $parser){

		$_lexer = $parser->getLexer();
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
	}
	
	public function getSql(){
		
		if (isset($_REQUEST['filter:transactionstatus:'.$this->columnName]) && !empty($_REQUEST['filter:transactionstatus:'.$this->columnName])) {
			$this->status = $_REQUEST['filter:transactionstatus:' . $this->columnName];
		}
		
		return ( $this->status == "" or is_null($this->status) )? " IS NOT NULL " : " = '".$this->status."'";
		
	}
	
	public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement(){
		
		$ret = '<div style="float:left; margin-right:10px;">
				<span>'.$this->getFilterLabel().'</span>
				<select name="filter:transactionstatus:' . $this->columnName . '"> 
						<option value=""> -- Select Transaction Status -- </option>';
		
		$transactionStatus = Transaction::$transaction_status_desc;
		
		
		foreach( $transactionStatus as $key => $val )
		{
			$sel = ($key == $this->status)? 'selected="selected"' : '';
			$ret .= '<option value="'.$key.'" '.$sel.' > '.$val.' </option>';
		}
		
		$ret .= '</select></div>';
		return $ret;
	}
	
	public function getFilterValue(){
		$status = (is_numeric($this->status)) ? Transaction::$transaction_status_desc[$this->status] : 'ANY';
		return '<p class="element"><label>'.$this->getFilterLabel().' </label>'.$status.'</p>';
	} 
}