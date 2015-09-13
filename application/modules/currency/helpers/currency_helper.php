<?php


function getCurrencySelectElement($name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $currencyRepo = $CI->doctrine->em->getRepository('currency\models\Currency');
    $currencies = $currencyRepo->findBy(
        [], //condition
        array('name' =>'ASC')
    );
    $options = array('' => '-- SELECT CURRENCY --');
    if( count($currencies) > 0 ){
        foreach($currencies as $currency){
            $options[$currency->id()] = $currency->getIso3();
        }
    }
    echo form_dropdown($name,$options,$selected,$attributes);
}

function getCurrencyMultiSelectElement($name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $currencyRepo = $CI->doctrine->em->getRepository('currency\models\Currency');
    $currencies = $currencyRepo->findBy(
        [], //condition
        array('name' =>'ASC')
    );
    $options = array();
    if( count($currencies) > 0 ){
        foreach($currencies as $currency){
            $options[$currency->id()] = $currency->getIso3();
        }
    }
    echo form_multiselect($name,$options,$selected,$attributes);
}