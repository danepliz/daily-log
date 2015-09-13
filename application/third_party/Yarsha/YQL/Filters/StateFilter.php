<?php
namespace Transborder\TQL\Filters;
use user\models\Group;

use Yarsha\YQL\Lexer;
//use models\Agent;

use Yarsha\YQL\YQLFilter;

class StateFilter extends YQLFilter
{
	
	private $columnName;
	
	private $country_alias;
	
	private $columnDesc;
	
	private $state = NULL;
	
	private $country = NULL;
	
	private $state_id = 0;
	
	public function parse(\Yarsha\YQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
		
		$filterLabel = explode(' ', $this->getFilterLabel());
		
		if(count($filterLabel)>1)
			$this->columnDesc = ucfirst(strtolower($filterLabel[0]));
		
		$this->country_alias = strtolower($filterLabel[count($filterLabel)-1]);
		
		$userRepo = \CI::$APP->doctrine->em->getRepository('models\User');
		
		$superUser = $userRepo->findOneBy(array('groups' => Group::SUPER_ADMIN));
		
		$this->country = $superUser->getCity()->getCountry()->id();
	}
	
	public function getSql(){
		
		$this->state = (isset($_REQUEST['filter:state:'.$this->columnName]) && !empty($_REQUEST['filter:state:'.$this->columnName])) ? $_REQUEST['filter:state:' . $this->columnName] : NULL;
		$this->country =  (isset($_REQUEST['filter:country:'.$this->columnName]) && !empty($_REQUEST['filter:country:'.$this->columnName])) ? $_REQUEST['filter:country:' . $this->columnName] : $this->country;

		if (!is_null($this->state)) 
		{
			return ' = '.$this->state;	
		}
		elseif(!is_null($this->country)) 
		{
			return ' IS NOT NULL AND '.$this->country_alias.'.id = '.$this->country;
		}
		else 
		{
			return ' IS NOT NULL ';
		}
	}
	
	public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement()
	{
		$ret = "";
		
		$ret .= '<div style="float:left; margin-right:10px;">
					<span>Country</span>
					<select name="filter:country:'.$this->columnName.'" id="country_filter">'; //<option value=""> Any Country </option>
		
		\CI::$APP->load->helper('country/country');
		$operatingCountries = getOperatingCountries();
		
		foreach($operatingCountries as $id => $name)
		{
			$sel = ($this->country == $id) ? 'selected="selected"' : '';
			$ret .= '<option value="'.$id.'" '.$sel.' > '.$name.' </option>';
		}
		
		$ret .= '</select></div>';
		
		$ret .= '<div style="float:left; margin-right:10px;">
					<span>State</span>
					<select name="filter:state:' . $this->columnName . '" id="states_filter"><option value=""> Any State </option>';
		$ret .= '</select></div>';
		
		$ret .= $this->getFilterScript();
		
		return $ret;
	}
	
	public function getFilterValue()
	{
		$country = (is_numeric($this->country)) ? \CI::$APP->doctrine->em->find('models\Common\Country', $this->country)->getName() : 'ANY';
		$state = (is_numeric($this->state)) ? \CI::$APP->doctrine->em->find('models\Common\State', $this->state) : NULL;
		$stateDesc =  ( !is_null( $state ) ) ? '['.$state->getShortName().'] '.$state->getName() : 'ANY';
		
		$opt = '<p class="element"><label>Country</label>  : '.$country.'</p>';
		$opt .= '<p class="element"><label>State</label>  : '.$stateDesc.'</p>';
		
		return $opt;
	}

	public function getFilterScript()
	{
			
		$filterScript = "<script>$(function() { ";
		
	
			$filterScript .= "$('#country_filter').live('change',function(){
				
				var _obj = $(this);
				var sel = _obj.val();
				
				if(sel !== '') {
					
					$.ajax({
						type : 'GET',
						url : Transborder.config.base_url+'country/ajax/getStates/'+sel+'/".$this->state."',
						success : function(res){
							$('#states_filter').html(res);
							}
						});
		
					} else {
						$('#states_filter').html('<option value=\"\"> Any State </option>');
					}
				});
			
				$('.filter-agent-country-".$this->columnName."').trigger('change');
			";
		
		$filterScript .= "$('#country_filter').val(".$this->country.").trigger('change')";
			
		$filterScript .= "});</script>";
		
		return $filterScript;
	}
	
}