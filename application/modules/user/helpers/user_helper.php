<?php

use user\models\Group;

function user_access($permission)
{
	if(!Current_User::user()) return FALSE;
	if(Current_User::user()->getGroup()->id() == 1)
		return TRUE;
	
	return Current_User::can($permission);
}

function config_access(){
	$listeners = Events::get_listeners('config_launcher_init');
	if($listeners){
		$launchers = array();
		foreach($listeners as $l){
			$launchers = $l($launchers);
		}
		
		if(count($launchers)){
			foreach($launchers as $ln){
				$permission = $ln['permission'];
				if(user_access($permission)) return TRUE;
			}			
		}
		
		return FALSE;
	}
}

function user_access_or($permissions = array()){

	if(Current_User::user()->getGroup()->id() == 1)
		return TRUE;
	foreach($permissions as $p){
		if(Current_User::can($p))
			return TRUE;
	}
	return FALSE;
}

function user_access_and($permissions = array()){

    if(Current_User::user()->getGroup()->id() == 1)
		return TRUE;
	foreach($permissions as $p){
		if(!Current_User::can($p))
			return FALSE;
	}
	return TRUE;
}

function report_access($reportID) {
	
		if (!Current_User::user()) return FALSE; 

		$report = CI::$APP->doctrine->em->find('report\models\Report',$reportID);
		
		$current_user_group = Current_User::user()->getGroup()->id();

        if( Current_User::user()->getGroup()->id() == 1 ) return TRUE;

		$permitted_groups = $report->getUserGroups();

		if (in_array($current_user_group, $permitted_groups)) return TRUE;
		
		return FALSE;
			
	}
	
function txnhalted(){

	if (\Options::get('isTxnHalt', '0')=='1') {
		
		$from 	= \Options::get('txn_halt_from', '0000-00-00 00:00');
		$to 	= \Options::get('txn_halt_to', '0000-00-00 00:00');
		
		$from_date = substr($from, 0, 10);
		$to_date = substr($to, 0, 10);
		
		if (isValidDate($from_date) and isValidDate($to_date)) 
			if ( (strtotime($from.':00') < time()) and (time() <  strtotime($to.':00')) ) return $to;
	
	}
	
	return FALSE;
	
}

function accessible_group_list($name, $selected = NULL, $attributes = NULL)
{
	$groupRepo = CI::$APP->doctrine->em->getRepository('user\models\Group');

	$groups = $groupRepo->getGroupList();
	
	$options = array('' => ' -- SELECT A GROUP -- '); $groupOptions = '<option value=""> -- SELECT GROUP -- </option>';
	if((\Current_User::isSuperUser() || user_access('add user of all roles'))){
		if($groups and count($groups) > 0)
		{

			foreach($groups as $gr)
			{
				if( $gr['group_id'] == Group::SUPER_ADMIN ) continue;
					
				$options[$gr['group_id']] = $gr['name'];
				$mtoOnly = ($gr['mtoOnly']) ? "Y" : "N";
				$sel = ($gr['group_id'] == $selected)? 'selected="selected"' : '';
				$groupOptions .= '<option value="'.$gr['group_id'].'" data-mto="'.$mtoOnly.'" '.$sel.' > '.strtoupper($gr['name']).' </option>';
			}
		}	
	}
	else{
		$gr =Current_User::user()->getGroup();
		$sel = ($gr->id() == $selected)? 'selected="selected"' : '';
		$groupOptions .= '<option value="'.$gr->id().'" data-mto="'.$gr->isMtoOnly().'" '.$sel.' > '.strtoupper($gr->getName()).' </option>';
	}
	
	echo '<select name="'.$name.'" '.$attributes.' >'.$groupOptions.'</select>';
	
}

function getBranchSelectElement($name, $selected = NULL, $attr = ''){
    $CI = CI::$APP;

    $branchRepo = $CI->doctrine->em->getRepository('branch\models\Branch');
    //$branches = $branchRepo->findAll(array(), array('name' => 'desc'));
    $branches = $branchRepo->findBy(
        array('status' => 1),
        array('id' => 'ASC')
    );

    $options = array();
    $options[''] = '-- SELECT BRANCH --';

    if( count($branches) > 0){
        foreach($branches as $b){
            $options[$b->id()] = $b->getName();
        }
    }

    echo form_dropdown($name, $options, $selected, $attr);

}

function getUserMultiselectElement($name, $selected = NULL, $attr = ''){

    $CI = CI::$APP;

    $userRepo = $CI->doctrine->em->getRepository('user\models\User');
    $users = $userRepo->findBy([ 'status' => \user\models\User::USER_STATUS_ACTIVE ],[ 'fullname' => 'ASC' ]);

    $options = [];

    foreach($users as $user){
        $options[$user->id()] = $user->getFullname(). ' - '. $user->getEmail();
    }

    echo form_multiselect($name, $options, $selected, $attr);

}


?>