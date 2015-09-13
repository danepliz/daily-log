<?php
namespace Reservation\TQL\Filters;
use Reservation\TQL\Lexer;
use agent\models\Agent;

use Reservation\TQL\TQLFilter;

class CityFilter extends TQLFilter
{
	
	private $columnName;
	
	private $state = NULL;
	
	private $city = NULL;
	
	public function parse(\Reservation\TQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];

	/*	
		if ($_REQUEST) {
			foreach (array_keys($_REQUEST) as $req)
				if ( substr($req, 0, strlen('filter:state:')) == 'filter:state:' && !empty($_REQUEST[$req]) ) {
				$this->state = $_REQUEST[$req];
				break;
			}
				
		}
		
		if (! $this->state)
			$this->state = \Current_User::user()->getAgent()->getState()->id();
	*/		
		
	}
	
	public function getSql(){

		if (isset($_REQUEST['filter:city:'.$this->columnName]) && !empty($_REQUEST['filter:city:'.$this->columnName])) {
				
			$city = $_REQUEST['filter:city:' . $this->columnName];
			$this->city = $city;
			return  "= '".$city."'";
		}
		$this->city = 'IS NOT NULL';
		return 'IS NOT NULL';
		
	}
	
	public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement()
	{
		$ret = '<div style="float:left; margin-right:10px;">
					<span>'.$this->getFilterLabel().'</span>
					<select name="filter:city:' . $this->columnName . '" id="cities_filter"><option value=""> Any City </option>';
		
		$repo = \CI::$APP->doctrine->em->getRepository('models\Common\City');
		$cities  = $repo->getCities($this->state);
		
		foreach($cities as $s)
		{
			$selString = ($this->city == $s->id())? 'selected="selected"' : '';
			$ret .= "<option value='{$s->id()}' {$selString} cid='{$s->getCountry()->id()}' sid='{$s->getState()->id()}'>{$s->getName()}</option>";
		}
		
		$ret .= '</select></div>';
		
		return $ret;
	}
	
	public function getFilterValue(){
		$city = (is_numeric($this->city)) ? \CI::$APP->doctrine->em->find('models\Common\City', $this->city)->getName() : 'ANY';
		return '<p class="element"><label>  : '.$this->getFilterLabel().'</label>  : '.$city.'</p>';
	} 
	
}