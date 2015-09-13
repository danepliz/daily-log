<?php

use user\models\Group;

function getTourActivitySelectElement($name, $selected = NULL, $attributes = NULL){
    $types = \file\models\TourFileActivity::$fileActivityTypes;
    $options = [ '' => '-- SELECT ACTIVITY TYPE --' ];
    foreach($types as $k => $v){
        $options[ $k ] = $v;
    }
    echo form_dropdown($name, $options, $selected, $attributes);
}

function getAgentSelectionElementForXO($name, $selected = NULL, $attributes = NULL, $userId = NULL){
    $CI = CI::$APP;

    $agentRepo = $CI->doctrine->em->getRepository('agent\models\Agent');
    $agents = $agentRepo->findBy([],['name' => 'ASC']);

    $options = array('' => ' -- ANY --');

    if( count($agents) > 0 ){
        foreach($agents as $agent){
            $options[$agent->id()]= $agent->getName();
        }
    }

    echo form_dropdown($name, $options,$selected, $attributes);
}

function getMarketSelectionElementForXo($name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $marketRepo = $CI->doctrine->em->getRepository('market\models\Market');
    $markets = $marketRepo->findBy(
        array('status' =>1),
        array('id' =>'ASC')
    );
    $options = array('' => ' -- ALL MARKET -- ');

    if( count($markets) > 0 ){
        foreach($markets as $market){
            $currency = $market->getCurrency() ? $market->getCurrency()->getIso3() : '';
            $label = ( $currency == '' ) ? $market->getName() : $market->getName() .' ( '.$currency.' ) ';
            $options[$market->id()] = $label;
        }
    }
    echo form_dropdown($name, $options, $selected, $attributes);
}

function getCountrySelectionElementForXo($name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $countryRepo = $CI->doctrine->em->getRepository('location\models\Country');
    $countries = $countryRepo->findAll();
    $options = array('' => ' -- SELECT NATIONALITY --');

    if(count($countries) > 0){
        foreach($countries as $country){
            $options[$country->id()] = $country->getNationality();
        }
    }
    echo form_dropdown($name, $options, $selected, $attributes);
}

function getHotelSelectionElementForXo($name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $hotelRepo = $CI->doctrine->em->getRepository('hotel\models\Hotel');
    //$hotels = $hotelRepo->findAll();
    $hotels = $hotelRepo->findBy(
        array('status'=>'ACTIVE'),
        array('id'=>'ASC')
    );
    $options = array('' => ' -- SELECT HOTEL --');

    if(count($hotels) > 0){
        foreach($hotels as $hotel){
            $options[$hotel->id()] = $hotel->getName();
        }
    }
    echo form_dropdown($name, $options, $selected, $attributes);
}

function getUserSelectElement($name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $userRepo = $CI->doctrine->em->getRepository('user\models\User');
    $users = $userRepo->findAll();
    $options = array('' => ' -- ANY USER --');
    if(count($users) > 0){
        foreach($users as $user){
            $options[$user->id()] = $user->getFullName();
        }
    }
    echo form_dropdown($name, $options, $selected, $attributes);
}

//function getMarketSelectionElementForXo($name, $selected = NULL, $attributes = NULL){
//    $CI = CI::$APP;
//    $marketRepo = $CI->doctrine->em->getRepository('market\models\Market');
//    $markets = $marketRepo->findBy(
//        array('status' =>1),
//        array('id' =>'ASC')
//    );
//    $options = array('' => ' -- ALL MARKET -- ');
//
//    if( count($markets) > 0 ){
//        foreach($markets as $market){
//            $options[$market->id()] = $market->getName();
//        }
//    }
//    echo form_dropdown($name, $options, $selected, $attributes);
//}

function getTourOfficersSelectElement($name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $userRepository = $CI->doctrine->em->getRepository('user\models\User');
//    $users = $userRepository->getUserList(NULL, NULL, array('group' => Group::USER_GROUP_TOUR_OFFICER));

    $users = $userRepository->getUserList();
    $options = array('' => '-- SELECT TOUR OFFICER --');

    if( count($users) > 0 ){
        foreach($users as $u){
            $options[$u['user_id']] = $u['fullname'];
        }
    }

    echo form_dropdown($name, $options, $selected, $attributes);

}