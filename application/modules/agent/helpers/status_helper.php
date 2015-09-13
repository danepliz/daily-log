<?php 

use agent\models\Agent;

function getAgentStatusOptions($name, $currentStatus = NULL, $attributes = NULL)
{
    $agentStatus = Agent::$status_desc;
	
	$options = array( '' => ' -- Select Status -- ');

    foreach($agentStatus as $key => $val){
        $options[$key] = $val;
    }

	echo form_dropdown($name, $options, $currentStatus, $attributes);
	
}