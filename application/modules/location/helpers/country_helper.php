<?php

function getCountries(){
	$CI = CI::$APP;
	$cRepo = $CI->doctrine->em->getRepository('location\models\Country');
	$countries = $cRepo->findBy(array(), array('name'=>'asc'));
	return $countries;
}

function getSelectCountry($name, $selected = NULL,$attributes = NULL){
	$CI = CI::$APP;
	$cRepo = $CI->doctrine->em->getRepository('location\models\Country');
	$countries = $cRepo->findAll();
	$options = array();
	foreach($countries as $c){
		$options[$c->id()] = $c->getName();
	}
	echo form_multiselect($name,$options,$selected,$attributes);
}

function getCountrySelectElement($name, $selected = NULL,$attributes = NULL){
    $CI = CI::$APP;
    $cRepo = $CI->doctrine->em->getRepository('location\models\Country');
    $countries = $cRepo->findAll();
    $options = array('' => '-- SELECT COUNTRY --');
    foreach($countries as $c){
        $options[$c->id()] = $c->getName();
    }

    echo form_dropdown($name, $options, $selected, $attributes);
}




function getSelectOperatingCountry($name, $selected = NULL,$attributes = NULL, $setAny = FALSE){
	
	$CI = CI::$APP;
	$poolCountry = Options::get('config_operating_countries',NULL);
	$countries = array();
	foreach ($poolCountry as $countryID) {
		$countries[] = $CI->doctrine->em->find('location\models\Country',$countryID);
	}
	$options = array();
	
	if($setAny) $options[0] = 'Any Country';
	
	foreach($countries as $c){
		$options[$c->id()] = $c->getName();
	}	
	echo form_multiselect($name,$options,$selected,$attributes);
}

function getOperatingCountries(){
	$CI = CI::$APP;
	$operating_countries = Options::get('config_operating_countries');
	$options = array();
	
	foreach($operating_countries as $c){
		$country = $CI->doctrine->em->find('location\models\Country',$c);
		$options[$c] = $country->getName();
	}
	
	asort($options);
	
	return $options;
}

function getTimezones(){
	$CI = CI::$APP;
	
	$cRepo = $CI->doctrine->em->getRepository('models\Common\TimeZone');
	$timezones = $cRepo->findAll();
	
	$options = array();
	
	foreach($timezones as $t){
		$options[$t->id()] = $t->getName();
	}
	
	return $options;
}

