<?php

namespace Reservation\TQL;

use Reservation\TQL\Formatters\AggregateField;

use Reservation\TQL\Formatters\ColumnFilter;

class Parser
{
	private $_input;
	
	private $_lexer;
	
	private $_parseResult;
	
	private static $_FILTERS = array(
			'date'		=>	'Transborder\TQL\Filters\DateFilter',
			'agent'		=>	'Transborder\TQL\Filters\AgentFilter',
			'country'	=>	'Transborder\TQL\Filters\CountryFilter',
			'or'		=> 'Transborder\TQL\Filters\OrFilter',
			'freetext'	=> 'Transborder\TQL\Filters\FreetextFilter',
			'state'		=> 'Transborder\TQL\Filters\StateFilter',
			'city'		=> 'Transborder\TQL\Filters\CityFilter',
			'countryagent' => 'Transborder\TQL\Filters\CountryAgentFilter',
			'amount' 	=> 'Transborder\TQL\Filters\AmountFilter',
			'agentstatus' => 'Transborder\TQL\Filters\AgentStatusFilter',
			'transactionstatus' => 'Transborder\TQL\Filters\TransactionStatusFilter',
		);
	
	public function __construct($input){
		$this->_input = $input;
		$this->_lexer = new \Transborder\TQL\Lexer($input);	
	}
	
	public function parse(){
		$this->_parseResult = new ResultSetFormatter();
		$this->transborderQuery();
		return $this->_parseResult;
	}
	
	public function match($token)
	{
		$lookaheadType = $this->_lexer->lookahead['type'];
	
		// short-circuit on first condition, usually types match
		if ($lookaheadType !== $token && $token !== Lexer::T_IDENTIFIER && $lookaheadType <= Lexer::T_IDENTIFIER) {
			$this->syntaxError($this->_lexer->getLiteral($token));
		}
	
		$this->_lexer->moveNext();
	}
	
	public function transborderQuery(){
		$this->_lexer->moveNext();
		
		if($this->_lexer->isNextToken(Lexer::T_TABLIZE)){
			$this->TabelizeByClause();
		}
		
		if($this->_lexer->isNextToken(Lexer::T_FILTERABLE)){
			$this->FilterableByClause();
		}
		
		if($this->_lexer->isNextToken(Lexer::T_FILTER)){
			$this->FilterStatement();
		}
		
		if($this->_lexer->isNextToken(Lexer::T_AGGREGATE)){
			$this->AggregateClause();
		}
// 		$statement = 
		
// 		switch($this->_lexer->lookahead['type']){
// 			case Lexer::T_TABELIZE:
// 				$this->TabelizeByClause();
// 			break;
			
// 			case Lexer::T_FILTERABLE:
				
// 			break;
// 		}
	}
	
	
	public function FilterStatement(){
		$this->match(Lexer::T_FILTER);
		$this->match(Lexer::T_COLON);
		
		$token = $this->_lexer->lookahead;
		$function = strtolower($token['value']);
		
		if(!isset(self::$_FILTERS[$function])){
			$this->syntaxError();
		}
		
		$funcParser = new self::$_FILTERS[$function]($function);
		$this->match(Lexer::T_COLON);
		$this->_lexer->moveNext();
		
		$funcParser->parse($this);
		$this->_parseResult->setFilter($funcParser);
// 		show_pre($lookaheadType);
	}
	
	public function TabelizeByClause(){
		$this->match(Lexer::T_TABLIZE);
		$this->match(Lexer::T_BY);
		
		$tabelizeByItems = array($this->TablizeByItem());
		
		while ($this->_lexer->isNextToken(Lexer::T_COMMA)) {
			$this->match(Lexer::T_COMMA);
			$tabelizeByItems[] = $this->TablizeByItem();
		}
	}
	
	public function AggregateClause(){
		$this->match(Lexer::T_AGGREGATE);
		
		$aggregateColumns = array($this->AggregateItem());
		while ($this->_lexer->isNextToken(Lexer::T_COMMA)) {
			$this->match(Lexer::T_COMMA);
			$aggregateColumns[] = $this->AggregateItem();
		}
	}
	
	private function AggregateItem(){
		$this->match(Lexer::T_STRING);
		$column = $this->_lexer->token['value'];
		
		if ($this->_lexer->isNextToken(Lexer::T_SUM)) {
			$this->_lexer->moveNext();
			$filter = new AggregateField($column, AggregateField::TYPE_SUM);
		}elseif($this->_lexer->isNextToken(Lexer::T_AVERAGE)){
			$this->_lexer->moveNext();
			$filter = new AggregateField($column, AggregateField::TYPE_AVERAGE);
		}elseif(!$this->_lexer->isNextToken(Lexer::T_STRING)){
			$this->syntaxError('Aggregate Function');
		}
		
		$this->_parseResult->addAggregateColumn($filter);
		return $column;
	}
	
	private function TablizeByItem(){
		$this->match(Lexer::T_STRING);
		$column = $this->_lexer->token['value'];
		$this->_parseResult->addTabelizerColumn($column);
		return $column;
	}
	
	public function FilterableByClause(){
		$this->match(Lexer::T_FILTERABLE);
		$this->match(Lexer::T_BY);

		$filterableByItems = array($this->FilterableByItem());
		while ($this->_lexer->isNextToken(Lexer::T_COMMA)) {
			$this->match(Lexer::T_COMMA);
			$filterableByItems[] = $this->FilterableByItem();
			
		}
	}
	
	private function FilterableByItem(){
		$this->match(Lexer::T_STRING);
		
		$column = $this->_lexer->token['value'];
		$type = NULL;
		
		$this->match(Lexer::T_TYPE);
		
		if ($this->_lexer->isNextToken(Lexer::T_DATE)) {
			$this->_lexer->moveNext();
			$filter = new ColumnFilter($column, ColumnFilter::FILTER_TYPE_DATE);
		}elseif($this->_lexer->isNextToken(Lexer::T_DATE)){
			$this->_lexer->moveNext();
			$filter = new ColumnFilter($column, ColumnFilter::FILTER_TYPE_COMBO);
		}elseif(!$this->_lexer->isNextToken(Lexer::T_STRING)){
			$this->syntaxError('Filter column identifier');
		}
		
		
		$this->_parseResult->addFilterableColumn($filter);
		return $column;
	}
	
	/**
	 * Gets the lexer used by the parser.
	 *
	 * @return \Doctrine\ORM\Query\Lexer
	 */
	public function getLexer(){
		return $this->_lexer;
	}
	
	
	public function syntaxError($expected = '', $token = null)
	{
		if ($token === null) {
			$token = $this->_lexer->lookahead;
		}
	
		$tokenPos = (isset($token['position'])) ? $token['position'] : '-1';
	
		$message  = "line 0, col {$tokenPos}: Error: ";
		$message .= ($expected !== '') ? "Expected {$expected}, got " : 'Unexpected ';
		$message .= ($this->_lexer->lookahead === null) ? 'end of string.' : "'{$token['value']}'";
	
		throw \Doctrine\ORM\Query\QueryException::syntaxError($message);
	}
}