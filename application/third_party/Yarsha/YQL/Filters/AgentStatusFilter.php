<?php
namespace Yarsha\YQL\Filters;
use user\models\Group;

use Transborder\YQL\Lexer;
//use models\Agent;

use Yarsha\YQL\YQLFilter;

class AgentStatusFilter extends YQLFilter
{
	
	private $columnName;
	
	private $status = NULL;
	
	public function parse(\Yarsha\YQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
	}
	
	public function getSql(){
		
		if (isset($_REQUEST['filter:agentstatus:'.$this->columnName]) && !empty($_REQUEST['filter:agentstatus:'.$this->columnName]) ) {
			$this->status = $_REQUEST['filter:agentstatus:' . $this->columnName];
			if ( $this->status == 101 ) return ' = 0';
		}
		
		return ( $this->status == "" or is_null($this->status) )? ' IS NOT NULL ' : ' = '.$this->status;
		
	}
	
	public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement(){
		
		$ret = '<div style="float:left; margin-right:10px;">
				<span>'.$this->getFilterLabel().'</span>
				<select name="filter:agentstatus:' . $this->columnName . '"> 
						<option value=""> -- Select Agent Status -- </option>';
		
		$agentStatus = Agent::$status_desc;
		
		unset($agentStatus[0]);
		
		$agentStatus[101] = 'Blocked';
		
		foreach( $agentStatus as $key => $val )
		{
			$sel = ( $this->status == $key )? 'selected="selected"' : '';
			$ret .= '<option value="'.$key.'" '.$sel.'> '.$val.' </option>';
		}
		
		$ret .= '</select></div>';
		return $ret;
	}
	
	public function getFilterValue(){
		$this->status = ( $this->status == '101' )? 0 : $this->status;
		$status = (is_numeric($this->status)) ? Agent::$status_desc[$this->status] : 'ANY';
		return '<p class="element"><label>'.$this->getFilterLabel().' </label>'.$status.'</p>';
	} 
}