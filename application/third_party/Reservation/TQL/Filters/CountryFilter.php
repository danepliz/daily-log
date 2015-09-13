<?php
namespace Reservation\TQL\Filters;
use user\models\Group;

use Reservation\TQL\Lexer;
use agent\models\Agent;

use Reservation\TQL\TQLFilter;

class CountryFilter extends TQLFilter
{
	
	private $columnName;
	
	private $country = NULL;
	
	public function parse(\Reservation\TQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
		
		$userRepo = \CI::$APP->doctrine->em->getRepository('user\models\User');
		
		$superUser = $userRepo->findOneBy(array('groups' => Group::SUPER_ADMIN));
		
		$this->country = $superUser->getCity()->getCountry()->id();
	
	}
	
	public function getSql(){
		
		if (isset($_REQUEST['filter:country:'.$this->columnName]) && !empty($_REQUEST['filter:country:'.$this->columnName])) {
			$this->country = $_REQUEST['filter:country:' . $this->columnName];
		}
		
		return ' = '.$this->country;
		
	}
	
	public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement(){
		
		$ret = '<div style="float:left; margin-right:10px;">
				<span>'.$this->getFilterLabel().'</span>
				<select name="filter:country:' . $this->columnName . '"> ';
		
		$operatingCountries = \Options::get('config_operating_countries');
		
		foreach ($operatingCountries as $c) {
				
			$country = \CI::$APP->doctrine->em->find('models\Common\Country', $c);
			$selectString = ($c == $this->country) ? 'selected="selected"' : '';
			if ($country)
				$ret .= "<option value='{$c}' {$selectString}> {$country->getName()}</option>";
		}
		
		$ret .= '</select></div>';
		return $ret;
	}
	
	public function getFilterValue(){
		$country = (is_numeric($this->country)) ? \CI::$APP->doctrine->em->find('models\Common\Country', $this->country)->getName() : 'ANY';
		return '<p class="element"><label>'.$this->getFilterLabel().'Country </label>'.$country.'</p>';
	} 
}