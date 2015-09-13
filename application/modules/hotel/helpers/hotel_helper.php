<?php

use hotel\models\Hotel;
use hotel\models\HotelPackage;

function getHotelGradeSelectElement($name, $selected = NULL, $attributes = '')
{

    $CI = & CI::$APP;

    $hotelBrandRepository = $CI->doctrine->em->getRepository('hotel\models\HotelGrade');
    $hotelBrands = $hotelBrandRepository->findBy(
        array('status' =>1),
        array('id' =>'ASC')
    );

    $options = array();
    $options[''] = ' -- SELECT HOTEL BRAND -- ';

    if( count($hotelBrands) ){
        foreach( $hotelBrands as $brand ){
            $options[$brand->id()] = strtoupper($brand->getName());
        }
    }

    echo form_dropdown($name, $options, $selected, $attributes);
}

function getHotelCategorySelectElement($name, $selected = NULL, $attributes = '')
{

    $CI = & CI::$APP;

    $hotelCategoryRepository = $CI->doctrine->em->getRepository('hotel\models\HotelCategory');
    $hotelCategories = $hotelCategoryRepository->findBy(
        array('status'=>1),
        array('id' => 'ASC')
    );

    $options = array();
    $options[''] = " -- SELECT HOTEL CATEGORY -- ";

    if( count($hotelCategories) ){
        foreach( $hotelCategories as $category ){
            $options[$category->id()] = strtoupper($category->getName());
        }
    }
    echo form_dropdown($name, $options, $selected, $attributes);
}

function getHotelStatusSelectElement($name, $selected = Hotel::HOTEL_STATUS_ACTIVE, $attributes){
    $statusArray = Hotel::$hotel_status;
    echo form_dropdown($name, $statusArray, $selected, $attributes);
}

function getSelectHotelRoomCategories($name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $roomCategoryRepository = $CI->doctrine->em->getRepository('hotel\models\HotelRoomCategory');
    $roomCategories = $roomCategoryRepository->findBy(
        array('status' => 1),
        array('id' =>'ASC')
    );

    $out = '<select multiple="multiple" name="'.$name.'" '.$attributes.'>';
    if( count($roomCategories) > 0 ){
        foreach($roomCategories as $category){
            $selectedOption = ( in_array($category->id(), $selected) )? 'selected="selected"': '';
            $out .= '<option value="'.$category->id().'"  '.$selectedOption.'>'.$category->getName().'</option>';
        }
    }
    $out .= '</select>';
    echo $out;

//    $options = array();
//    if( count($roomCategories) > 0 ){
//        foreach($roomCategories as $category){
//            $options[$category->id()] = $category->getName();
//        }
//    }
//    echo form_multiselect($name,$options,$selected,$attributes);

}

function getSelectHotelPayableCurrencies($name, $selected = NULL, $attributes = NULL){
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

function getSelectHotelRoomTypes($name, $selected = [], $attributes = NULL){
    $CI = CI::$APP;
    $roomTypeRepository = $CI->doctrine->em->getRepository('hotel\models\HotelRoomType');
    $roomTypes = $roomTypeRepository->findBy(
        array('status' => 1),
        array('id' =>'ASC')
    );

    $out = '<select name="'.$name.'" '.$attributes.' multiple>';
    if( count($roomTypes) > 0 ){
        foreach($roomTypes as $type){
            $selectedOption = ( in_array($type->id(), $selected) )? 'selected="selected"': '';
            $out .= '<option value="'.$type->id().'"  '.$selectedOption.'>'.$type->getName().'</option>';
        }
    }
    $out .= '</select>';
    echo $out;
}

function getSelectHotelRoomPlans($name, $selected = array(), $attributes = NULL){
    $CI = CI::$APP;
    $roomPlanRepository = $CI->doctrine->em->getRepository('hotel\models\HotelRoomPlan');
    $roomPlans = $roomPlanRepository->findBy(
        array('status' => 1),
        array('id' =>'ASC')
    );

    $out = '<select name="'.$name.'" '.$attributes.'multiple>';

    if( count($roomPlans) > 0 ){
        foreach($roomPlans as $plan){
            $selectedOption = ( in_array($plan->id(), $selected) )? 'selected="selected"': '';
            $out .= '<option value="'.$plan->id().'"  '.$selectedOption.'>'.$plan->getName().'</option>';
        }
    }

    $out .= '</select>';
    echo $out;
}

function getSelectMarketElement($name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $marketRepo = $CI->doctrine->em->getRepository('market\models\Market');
    //$markets = $marketRepo->findAll(array(), array('name' => 'asc'));
    $markets = $marketRepo->findBy(
        array('status' =>1),
        array('name' =>'ASC')
    );

    $options = array('' => ' -- CHOOSE ONE -- ');

    if( count($markets) > 0 ){
        foreach($markets as $market){
            if( $market->getCurrency() ){
                $currencyCode = $market->getCurrency()->getIso3();
            }else{
                $currencyID = Options::get('config_market_currency', '');
                $currencyObj = ( $currencyID != '' )? $CI->doctrine->em->find('currency\models\Currency',$currencyID) : NULL;
                $currencyCode = ( $currencyObj )? $currencyObj->getIso3() : 'USD';
            }
            $options[$market->id()] = $market->getName() .' - '. $currencyCode;
        }
    }

    echo form_dropdown($name, $options, $selected, $attributes);
}

function getSelectServiceElement($name, $selected = NULL, $attributes=NULL)
{
  $CI = CI::$APP;
    $serviceRepo = $CI->doctrine->em->getRepository('hotel\models\HotelServices');
    $services = $serviceRepo->findBy(
      array('status'=>1),
        array('name'=> 'Asc')

    );
    $options = array(''=>'Select Below');
    if(count($services) > 0){
        foreach($services as $service){
           $options[$service->id()] = $service->getName();
        }
    }
    echo form_dropdown($name, $options, $selected, $attributes);
}



function getSelectRoomCategoriesByHotel($hotelID, $name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $hotel = $CI->doctrine->em->find('hotel\models\Hotel', $hotelID);
    $categories = $hotel->getRoomCategories();
    $options = array('' => ' -- ALL -- ');

    if( count($categories) > 0 ){
        foreach($categories as $category){
            $options[$category->id()] = $category->getName();
        }
    }

    echo form_dropdown($name, $options, $selected, $attributes);
}


function getSelectOutletsByHotel($hotelID, $name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $hotel = $CI->doctrine->em->find('hotel\models\Hotel', $hotelID);
    $outlets = $hotel->getOutlets();
    $options = array('' => ' -- ALL -- ');

    if( count($outlets) > 0 ){
        foreach($outlets as $outlet){
            if( $outlet->getStatus() == FALSE ) continue;
            $options[$outlet->id()] = $outlet->getName();
        }
    }

    echo form_dropdown($name, $options, $selected, $attributes);
}

function getSelectServicesByHotel($hotelID, $name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $hotel = $CI->doctrine->em->find('hotel\models\Hotel', $hotelID);
    $services = $hotel->getServices();
    $options = array('' => ' -- ALL -- ');

    if( count($services) > 0 ){
        foreach($services as $service){
            if( $service->getStatus() == FALSE ) continue;
            $options[$service->id()] = $service->getName();
        }
    }

    echo form_dropdown($name, $options, $selected, $attributes);
}

function getSelectRoomTypesByHotel($hotelID, $name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $hotel = $CI->doctrine->em->find('hotel\models\Hotel', $hotelID);
    $types = $hotel->getRoomTypes();
    $options = array('' => ' -- ALL -- ');

    $output = '<select name="'.$name.'" '.$attributes. '>';
    $output .= '<option value="" data-qty="0"> -- ALL -- </option>';


    if( count($types) > 0 ){
        foreach($types as $type){
//            $options[$type->id()] = $type->getName();
            $sel = ( $selected and $selected == $type->id() )? 'selected="selected"' : '';
            $output .= '<option value="'.$type->id().'" data-qty="'.$type->getQuantity().'" '.$sel.'> '.$type->getName().' </option>';
        }
    }
    $output .= '</select>';

    echo $output;

//    echo form_dropdown($name, $options, $selected, $attributes);
}

function getSelectRoomPlansByHotel($hotelID, $name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $hotel = $CI->doctrine->em->find('hotel\models\Hotel', $hotelID);
    $plans = $hotel->getRoomPlans();
    $options = array('' => ' -- ALL -- ');

    if( count($plans) > 0 ){
        foreach($plans as $plan){
            $options[$plan->id()] = $plan->getName();
        }
    }

    echo form_dropdown($name, $options, $selected, $attributes);
}

function getSelectHotelStatus($name, $selected = NULL, $attributes = NULL){
    $options = array('' => ' Select Status ');

    foreach(Hotel::$hotel_status as $k => $v){
        $options[$k] = $v;
    }

    echo form_dropdown($name, $options, $selected, $attributes);
}

function getSelectHotel($name, $selected = NULL, $attributes = NULL, $activeonly=FALSE){
    $CI = CI::$APP;

    $hotelRepository = $CI->doctrine->em->getRepository('hotel\models\Hotel');

    $filters = array();

    if($activeonly){
        $filters['status']= Hotel::HOTEL_STATUS_ACTIVE;
    }

    $hotels = $hotelRepository->listHotels(NULL, NULL, $filters);

    $options = array('' => ' -- SELECT HOTEL --');

    if( count($hotels) > 0 ){
        foreach($hotels as $h){
            $options[$h->id()] = strtoupper($h->getName());
        }
    }

    echo form_dropdown($name, $options, $selected, $attributes);
}

function calculatePayableAmount($charge, $percent){
    $return = ($charge + ( $percent / 100 ) * $charge );

    return number_format($return, 3, '.','');
}

function getSelectPayableCurrenciesByHotel($hotelID, $name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $hotel = $CI->doctrine->em->find('hotel\models\Hotel', $hotelID);
    $currencies = $hotel->getPayableCurrencies();
    $options = array('' => ' -- ALL -- ');

    if( count($currencies) > 0 ){
        foreach($currencies as $cur){
            $options[$cur->id()] = $cur->getIso3();
        }
    }

    echo form_dropdown($name, $options, $selected, $attributes);
}

function getSelectCurrenciesElement($name, $selected = NULL, $attributes = NULL){
    $CI = CI::$APP;
    $currencyRepo = $CI->doctrine->em->getRepository('currency\models\Currency');
    $currencies = $currencyRepo->findAll();
    $options = array('' => ' -- ALL -- ');

    if( count($currencies) > 0 ){
        foreach($currencies as $cur){
            $options[$cur->id()] = $cur->getIso3();
        }
    }

    echo form_dropdown($name, $options, $selected, $attributes);
}

function getSelectPackagesElementByHotel($hotelID, $name, $type = HotelPackage::PACKAGE_TYPE_MAIN, $selected = NULL, $attributes = NULL){
    $options = ['' => '-- ALL --'];
    $CI = CI::$APP;
    $packageRepo = $CI->doctrine->em->getRepository('hotel\models\HotelPackage');
    $packages = $packageRepo->findBy([ 'hotel' => $hotelID, 'type' => $type ]);
    if( count($packages) ){
        foreach($packages as $package){
            $options[$package->id()] = $package->getName();
        }
    }

    echo form_dropdown($name, $options, $selected, $attributes);
}
