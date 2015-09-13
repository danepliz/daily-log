<?php

function transport_permissions()
{
    return array(
        'view transport' => 'list and view transport details',
        'administer transport'	=>	'Create, edit and delete transports.'
//        'view hotel rates'  =>  'View rates of hotels',
//        'manage hotel rates' => 'add, update hotel rates',
//        'manage hotel rooms' => 'add, update hotel rooms',
//        'manage hotel services'=>'add, update hotel services'
    );
}

$transportMainMenu = new MainMenuItem();
$transportMainMenu->setId('MM_TRANSPORT');
$transportMainMenu->setName('Transports');
$transportMainMenu->setIcon('fa-cab');
$transportMainMenu->setRoute(site_url('transport'));
$transportMainMenu->setPermissions(array('administer transport','view transport'));
MainMenu::register($transportMainMenu);


$transportListMenu = new MainMenuItem();
$transportListMenu->setId('MM_TRANSPORT_LIST');
$transportListMenu->setName('List Transports');
$transportListMenu->setIcon('fa-list');
$transportListMenu->setRoute(site_url('transport'));
$transportListMenu->setPermissions(array('view transport'));
$transportListMenu->setParent($transportMainMenu);
MainMenu::register($transportListMenu);

$transportAddMenu = new MainMenuItem();
$transportAddMenu->setId('MM_TRANSPORT_ADD');
$transportAddMenu->setName('ADD Transports');
$transportAddMenu->setIcon('fa-plus-square');
$transportAddMenu->setRoute(site_url('transport/add'));
$transportAddMenu->setPermissions(array('administer transport'));
$transportAddMenu->setParent($transportMainMenu);
MainMenu::register($transportAddMenu);


//$hotelAddMenu = new MainMenuItem();
//$hotelAddMenu->setId('MM_HOTEL_ADD');
//$hotelAddMenu->setName('Add Hotel');
//$hotelAddMenu->setIcon('fa-plus-square');
//$hotelAddMenu->setRoute(site_url('hotel/add'));
//$hotelAddMenu->setPermissions(array('administer hotel'));
//$hotelAddMenu->setParent($hotelMainMenu);
//MainMenu::register($hotelAddMenu);
//
//$hotelCategoryMenu = new MainMenuItem();
//$hotelCategoryMenu->setId('MM_HOTEL_CATEGORY');
//$hotelCategoryMenu->setName('Hotel Category');
//$hotelCategoryMenu->setIcon('fa-sitemap');
//$hotelCategoryMenu->setRoute(site_url('hotel/category'));
//$hotelCategoryMenu->setPermissions(array('administer hotel'));
//$hotelCategoryMenu->setParent($hotelMainMenu);
//MainMenu::register($hotelCategoryMenu);
//
//$hotelGradeMenu = new MainMenuItem();
//$hotelGradeMenu->setId('MM_HOTEL_GRADE');
//$hotelGradeMenu->setName('Hotel Grade');
//$hotelGradeMenu->setIcon('fa-star');
//$hotelGradeMenu->setRoute(site_url('hotel/grade'));
//$hotelGradeMenu->setPermissions(array('administer hotel'));
//$hotelGradeMenu->setParent($hotelMainMenu);
//MainMenu::register($hotelGradeMenu);
//
//$hotelRoom = new MainMenuItem();
//$hotelRoom->setId('MM_HOTEL_ROOM');
//$hotelRoom->setName('Hotel Rooms');
//$hotelRoom->setIcon('fa-home');
//$hotelRoom->setRoute(site_url('hotel/room'));
//$hotelRoom->setPermissions(array('manage hotel rooms'));
//$hotelRoom->setParent($hotelMainMenu);
//MainMenu::register($hotelRoom);

//$hotelRates = new MainMenuItem();
//$hotelRates->setId('MM_HOTEL_RATES');
//$hotelRates->setName('Hotel Rates');
//$hotelRates->setIcon('fa fa-usd');
//$hotelRates->setRoute(site_url('hotel/rate'));
//$hotelRates->setPermissions(array('view hotel rates'));
//$hotelRates->setParent($hotelMainMenu);
//MainMenu::register($hotelRates);

//$hotelServices = new MainMenuItem();
//$hotelServices->setId('MM_HOTEL_SERVICES');
//$hotelServices->setName('Hotel Services');
//$hotelServices->setIcon('fa fa-shirtsinbulk');
//$hotelServices->setRoute(site_url('hotel/service'));
//$hotelServices->setPermissions(array('administer hotel'));
//$hotelServices->setParent($hotelMainMenu);
//MainMenu::register($hotelServices);

