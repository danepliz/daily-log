<?php
namespace Transborder\TQL\Filters;
use user\models\Group;

use Yarsha\YQL\Lexer;

use Yarsha\YQL\YQLFilter;

class OrFilter extends YQLFilter
{
	
	private $dbAlias;
	
	private $dbColumn;
	
	private $columnName;
	
	private $filterName;
	
	private $filterValue = '';
	
	
	public function parse(\Yarsha\YQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		$this->dbAlias = $_lexer->lookahead['value'];
		
		$_lexer->moveNext();
		$_lexer->moveNext();
		$this->dbColumn = $_lexer->lookahead['value'];
		
		$_lexer->moveNext();
		$_lexer->moveNext();
		$this->filterName  = $_lexer->lookahead['value'];
		$_lexer->moveNext();
		$_lexer->moveNext();
		$this->columnName = $_lexer->lookahead['value'];
		
	}
	
	public function getSql(){
		
		if (isset($_REQUEST['filter:'. $this->filterName . ':' . $this->columnName]) && !empty($_REQUEST['filter:'. $this->filterName . ':' . $this->columnName])) {
			
			$request = $_REQUEST['filter:'. $this->filterName . ':' . $this->columnName];
			$this->filterValue = $request;
			
			if ($this->filterName == 'agent') {
				if ( isset($_REQUEST['filter:subagent:' . $this->columnName]) && !empty($_REQUEST['filter:subagent:' . $this->columnName]) ) {
					return "";
				}
				
				if (\Current_User::user()->getGroup()->id() != Group::SUPER_ADMIN or \Current_User::user()->getGroup()->id() != Group::SUPER_AGENT_ADMIN) {} else {
					return "";				
				}
				
			}
			
			return "or {$this->dbAlias}.{$this->dbColumn} = '". $this->filterValue ."'";
			
		} 
		
		return "";
		
	}
	
	public function getFilterLabel(){
		return FALSE;
	}
	
	public function getFilterElement(){
		return FALSE;
	}
	
	
	public function getFilterValue(){
		return FALSE;
	}
}