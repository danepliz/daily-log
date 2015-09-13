<?php
namespace Transborder\YQL\Filters;
//use models\User;

use user\models\Group;

use Yarsha\YQL\Lexer;

use Yarsha\YQL\YQLFilter;

class AgentFilter extends YQLFilter
{
	
	private $columnName;
	
	private $columnDesc;
	
	private $country_alias;
	
	private $country = NULL;
	
	private $agent = NULL;
	
	public $subagent = NULL;
	
	public function parse(\Yarsha\YQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		
		$parser->match(Lexer::T_IDENTIFIER);
		
		$this->columnName = $_lexer->token['value'];
		
		$filterLabel = explode(' ', $this->getFilterLabel());
		
		if(count($filterLabel)>2)
			$this->columnDesc = ucfirst(strtolower($filterLabel[0]));
		
		$this->country_alias = strtolower($filterLabel[count($filterLabel)-1]);
		
		$userRepo = \CI::$APP->doctrine->em->getRepository('models\User');
		
		$superUser = $userRepo->findOneBy(array('groups' => Group::SUPER_ADMIN));
		
		$this->country = $superUser->getCity()->getCountry()->id();
	}
	
	public function getSql()
	{
		$this->country = (isset($_REQUEST['filter:country:'.$this->columnName]) && !empty($_REQUEST['filter:country:'.$this->columnName]))? $_REQUEST['filter:country:'.$this->columnName] : $this->country;
		
		$this->agent = (isset($_REQUEST['filter:agent:'.$this->columnName]) && !empty($_REQUEST['filter:agent:'.$this->columnName]))? $_REQUEST['filter:agent:'.$this->columnName] : NULL;
		
		$this->subagent = (isset($_REQUEST['filter:subagent:'.$this->columnName]) && !empty($_REQUEST['filter:subagent:'.$this->columnName]))? $_REQUEST['filter:subagent:'.$this->columnName] : NULL;
		
		$agent = \Current_User::user()->getAgent();
		
		if(!\Current_User::isSuperUser() and !\Current_User::isMTOUser() and \Current_User::user()->getGroup()->id() != Group::SUPER_AGENT_ADMIN and \Current_User::user()->getLevel() != User::USERLEVEL_PA)
		{
			$this->country = $agent->getCountry()->id();
				
			$this->agent = ($agent->getParentAgent())? $agent->getParentAgent()->id() : $agent->id();
				
			$this->subagent = $agent->id();
		}
		
		if(!\Current_User::isMTOUser() and (\Current_User::user()->getGroup()->id() == Group::SUPER_AGENT_ADMIN or \Current_User::user()->getLevel() == User::USERLEVEL_PA))
		{
			$this->country = $agent->getCountry()->id();
			
			$this->agent = ($agent->getParentAgent())? $agent->getParentAgent()->id() : $agent->id();
		}
		
		if($this->subagent) return "= '".$this->subagent."'";
		
		if($this->agent)
		{
			$pa = \CI::$APP->doctrine->em->find('models\Agent', $this->agent );
				
			$sa = $pa->getSubAgents();
				
			$cond = "";
				
			foreach($sa as $sagent)
			{
				$cond .= $sagent->id().',';
			}
			return " in (".$cond.$this->agent.")";
		}
		
		if($this->country)
		{
			return "IS NOT NULL AND ".$this->country_alias.".id = '".$this->country."'";
		}
		
		return "IS NOT NULL";
	}
	
	public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement(){
		
		$currentUser = \Current_User::user();
		$currentUserGroup = $currentUser->getGroup()->id();
		$currentUserAgent = $currentUser->getAgent();
		$currentUserCountry = $currentUserAgent->getCountry();
		$currentUserPA = ($currentUserAgent->getParentAgent())? $currentUserAgent->getParentAgent() : $currentUserAgent;
		$currentSubAgents = $currentUserPA->getSubAgents();
		
		$agents = array();
		$countries = array();
		$subagents = array();
		
		$ret = "";
		
		if(\Current_User::isSuperUser() or \Current_User::isMTOUser())
		{
			$operatingCountries = \Options::get('config_operating_countries');
			if(count($operatingCountries)>0)
			{
				foreach($operatingCountries as $k => $v )
				{
					$country = \CI::$APP->doctrine->em->find('models\Common\Country', $v);
					$countries[] = array('id' => $country->id(), 'name' => $country->getName());
				}
			}
		}
		elseif($currentUserGroup == Group::SUPER_AGENT_ADMIN or \Current_User::user()->getLevel() == User::USERLEVEL_PA)
		{
			$countries[] = array('id' => $currentUserCountry->id(), 'name' => $currentUserCountry->getName()); 
			$agents[] = array('id' => $currentUserPA->id(), 'name' => $currentUserPA->getName());
			foreach($currentSubAgents as $csa)
			{
				$subagents[] = array('id' => $csa->id(), 'name' => $csa->getName(), 'address' => $csa->getAddress());
			}
		}
		else 
		{
			$countries[] = array('id' => $currentUserCountry->id(), 'name' => $currentUserCountry->getName());
			$agents[] = array('id' => $currentUserPA->id(), 'name' => $currentUserPA->getName());
			$subagents[] = array('id' => $currentUserAgent->id(), 'name' => $currentUserAgent->getName(),  'address' => $currentUserAgent->getAddress());
		}
		
		
		
		/* Country Filter */
		$ret .= '<div style="float:left; margin-right:10px;"><span>'.$this->columnDesc.' Country</span>
					<select agent= "'.$this->agent.'" class="filter-agent-country-'.$this->columnName.'" name="filter:country:'.$this->columnName.'">';
// 		if(count($countries) >1){ $ret .= '<option value=""> Any Country </option>'; }
		
		foreach($countries as $con) 
		{ 
			$sel = ($this->country == $con['id'])? 'selected="selected"' : '';
			
			$ret .= '<option value="'.$con['id'].'" '.$sel.'> '.$con['name'].' </option>';
		}
		$ret .= '</select></div>';
		
		/* Agent Filter */
		$ret .= '<div style="float:left; margin-right:10px;"><span>'.$this->columnDesc.' Processor</span>
					<select subagent="'.$this->subagent.'" class="filter-pagent-'.$this->columnName.'" name="filter:agent:' . $this->columnName . '">';
		
		if(empty($agents)){ $ret .= '<option value=""> Any Processor </option>'; }
		
		foreach($agents as $aa)
		{
			$sel = ($this->agent == $aa['id'])? 'selected="selected"' : '';
			$ret .= '<option value="'.$aa['id'].'" '.$sel.'> '.$aa['name'].' </option>';
		}
		
		$ret .= '</select></div>';
		
		/* Sub - Agent Filter */
		$ret .= '<div style="float:left; margin-right:10px;"><span>'.$this->columnDesc.' Agent Location</span>
						<select class="filter-sagent-'.$this->columnName.'" name="filter:subagent:'.$this->columnName.'">';
		
		if(empty($subagents) or count($subagents) > 1)
		{
			$ret .= '<option value=""> Any Agent Location </option>';
		}
		
		foreach($subagents as $saa)
		{
			$sel = ($this->subagent == $saa['id'])? 'selected="selected"' : '';
			$ret .= '<option value="'.$saa['id'].'" '.$sel.'> '.$saa['address'].' - '.$saa['name'].' </option>';
		}
		
		$ret .= '</select></div>';
		
		$ret .= $this->getFilterScript();
		
		return $ret;
	}
	
	public function setDefaults() {
		
		if (! isset($_REQUEST['filter:agent:'.$this->columnName])) {
			
			if ( \Current_User::isSuperUser() or \Current_User::isMTOUser()) {
				$this->agent = 'IS NOT NULL';
				
			} else {
				
				$agent = \Current_User::user()->getAgent();
				$subagent = $agent->id();
				$agent = ($pagent = $agent->getParentAgent()) ? $pagent : $agent;
				$this->agent = $agent->id();
				
				if (\Current_User::isSubagentUser()) {
					$this->subagent = $subagent;
				}
				
			}
			
		}
	}
	
	public function getFilterValue(){
		$ci = \CI::$APP;
		
		$agentDesc = 'ANY';
		$subagentDesc = 'ANY';
		
		log_message('info', $this->agent);
		log_message('info', $this->subagent);
		log_message('info', $this->country);
		
		if((is_numeric($this->agent)))
		{
			$pagent = $ci->doctrine->em->find('models\Agent', $this->agent);
			$agentDesc = $pagent->getBranchCode().' ['.$pagent->getName().']';
		}
		
		if((is_numeric($this->subagent)))
		{
			$sagent = $ci->doctrine->em->find('models\Agent', $this->subagent);
			$subagentDesc = $sagent->getBranchCode().' ['.$sagent->getName().']';
		}
		
		$country = (is_numeric($this->country)) ? $ci->doctrine->em->find('models\Common\Country', $this->country) : 'ANY';
		
		$opt = '<p class="element"><label>'.$this->columnDesc.' Country </label>  : '.$country.'</p>';
		$opt .= '<p class="element"><label>'.$this->columnDesc.' Processor </label>  : '.$agentDesc.'</p>';
		$opt .= '<p class="element"><label>'.$this->columnDesc.' Agent Location </label>  : '.$subagentDesc.'</p>';
		log_message('info', $opt);
		return $opt;
	}
	
	public function getFilterScript()
	{
			
		$filterScript = "<script>$(function() {";
		
		if(\Current_User::isSuperUser() or \Current_User::isMTOUser())
		{
			$filterScript .= "$('.filter-agent-country-".$this->columnName."').live('change',function(){
				
				var _obj = $(this);
				var sel = _obj.val();
				var agent = $('.filter-pagent-".$this->columnName."');
				
				if(sel !== '') {
					
					$.ajax({
						
						type : 'GET',
						url : Transborder.config.base_url+'agent/ajax/getAgentsByCountry/'+sel+'/0',
						success : function(res){
							res = $.parseJSON(res);
								var ht = \"<option value=''> -- Select Principal Agent -- </option>\";	
								ht += Transborder.utility.buildSelectOption(res,function(item,option){
										if(option == 'value') return item.id;
										else return item.name;
									});
								
								agent.html(ht);
								agent.val(_obj.attr('agent'));
								$('.filter-pagent-".$this->columnName."').trigger('change');
							}
							});
		
					} else {
						_obj.attr('agent','');
						$('.filter-pagent-".$this->columnName."').attr('subagent','');
						agent.html('<option value=\"\"> -- Select Country first -- </option>');
						$('.filter-sagent-".$this->columnName."').html('<option value=\"\"> -- Select Principal Agent first -- </option>');
					}
				});
			
				$('.filter-agent-country-".$this->columnName."').trigger('change');
			";
		}
		
		
		if(\Current_User::isSuperUser() or \Current_User::isMTOUser() or \Current_User::user()->getGroup()->id() == Group::SUPER_AGENT_ADMIN or \Current_User::user()->getLevel() == User::USERLEVEL_PA)
		{
			$filterScript .=" $('.filter-pagent-".$this->columnName."').live('change',function(){
				var _obj = $(this);
				var sel = _obj.val();
				var subagent = $('.filter-sagent-".$this->columnName."');
				
				if(sel !== '') {
					
				$.ajax({
				
				type : 'GET',
				url : Transborder.config.base_url+'agent/ajax/getsubagents/'+sel,
				success : function(res){
				res = $.parseJSON(res);
				var ht = \"<option value=''> -- Select Sub Agent -- </option>\";
				ht += Transborder.utility.buildSelectOption(res,function(item,option){
				if(option == 'value') return item.id;
				else return item.name;
				});
				
				subagent.html(ht);
				subagent.val(_obj.attr('subagent'));
				}
				});
				
				} else {
				_obj.attr('subagent','');
				subagent.html('<option value=\"\"> -- Select Principal Agent first -- </option>');
				}
			});";
		}
		
		
		
		$filterScript .= "});</script>";
		
		return $filterScript;
	}
}