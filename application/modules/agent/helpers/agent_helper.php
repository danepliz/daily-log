<?php
use agent\models\Agent;
use user\models\Group;


function getAgentDropDown($name, $selected = NULL, $attributes = ""){

    $CI = CI::$APP;
    $agentRepo = $CI->doctrine->em->getRepository('agent\models\Agent');
    $agents = $agentRepo->listPrincipalAgents();

    $options = array('' => '-- ANY AGENT --');

    if( count($agents) > 0 ){
        foreach( $agents as $agent ){
            $options[$agent['agent_id']] = $agent['name'];
        }
    }

    echo form_dropdown($name, $options, $selected, $attributes);
}

function getAgentByAdminType($destCountry){
	
	$ci = & CI::$APP;
	
	$arepo = $ci->doctrine->em->getRepository('models\Agent');
	$currentUser = Current_User::user();
	$userGroup = $currentUser->getGroup();
	$group_id = $userGroup->id();
	
	$agents = NULL;
	
	if($group_id == Group::SUPER_ADMIN){
		$agents = $arepo->getAgentsByCountry($destCountry, 0);
	}
	
	if($group_id == Group::SUPER_AGENT_ADMIN){
		$agents = ($currentUser->getAgent()->getParentAgent()) ? $currentUser->getAgent()->getParentAgent() : $currentUser->getAgent();
	}
	
	
	return $agents;
	
	
}

function getPrincipalAgentsByCurrentUserType()
{

	$ci = CI::$APP;
	
	$current_user = Current_User::user();
	$user_group = $current_user->getGroup();
	$group_id = $user_group->id();
	$current_agent = $current_user->getAgent();
	
	$agents = NULL;
	$arepo = $ci->doctrine->em->getRepository('models\Agent');
	
	if($group_id == Group::SUPER_ADMIN)
	{
		$agents = $arepo->findBy(array('parentAgent' => NULL));
	}
	else
	{
		$agents = (!is_null($current_agent->getParentAgent()))? $current_agent->getParentAgent() : $current_agent;		
	}
	return $agents;
	
}

function getAgentStatusString($status)
{
	return Agent::$status_desc[$status];
}

function getGroupsDropDown($type, $name, $selected = NULL, $attributes = NULL)
{
	$ci = CI::$APP;
	
	$gRepo = $ci->doctrine->em->getRepository('models\AgentGroup');
	
	$groups = $gRepo->findBy(array('group_type' => $type), array('name' => 'asc'));
	
	$options = array('' => ' -- Select Group -- ');
	
	if($groups)
	{
		foreach($groups as $group)
		{
			$options[$group->id()] = $group->getName();
		}
	}
	
	echo form_dropdown($name, $options, $selected, $attributes);
}

function getPrincipalAgentsDropDown($name, $selected = NULL, $attributes = NULL)
{
	$ci = CI::$APP;
	
	$aRepo = $ci->doctrine->em->getRepository('agent\models\Agent');
	$agents = $aRepo->getPrincipalAgents();
	
	$options = array('' => ' -- Select Agent -- ');
	
	if($agents)
	{
		foreach($agents as $agent)
		{
			$options[$agent->id()] = $agent->getName().' - '.$agent->getAddress();
		}
	}
	
	echo form_dropdown($name, $options, $selected, $attributes);
}

function getOnlineAgentByCountry( $country_id )
{
	$ci = & CI::$APP;
	
	$country = $ci->doctrine->em->find('models\Common\Country', $country);
	
	$onlineAgent = $country->getOnlineAgent();
	
	return $onlineAgent;
	
}

function getAgentsAvailableBalance($agentId)
{
	$CI = CI::$APP;
	$agent = $CI->doctrine->em->find('models\Agent', $agentId);
	
	$tRepo = $CI->doctrine->em->getRepository('models\Agent');
	$txnCounts = $tRepo->getAgentTransactionCount($agent->id());
	
	$remittedAmount = $txnCounts['remit']['total_amount'];
	$serviceCharge = $txnCounts['remit']['service_charge'];
	$remittedAmount = $remittedAmount + $serviceCharge;
	
	$paidAmount = $txnCounts['payout']['total_amount'];
	
	$currency = $agent->getCountry()->getCurrency()->getIsoCode();
	
	$availableBalance = $agent->getCreditLimit() + ($remittedAmount - $paidAmount);
	
	$availableBalance = $currency.' '.$availableBalance;
	
	return $availableBalance;
}