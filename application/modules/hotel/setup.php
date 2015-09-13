<?php

function hotel_permissions()
{
	return array(
        'view hotel' => 'list and view hotel details',
        'administer hotel'	=>	'Create, edit and delete hotels.',
        'view hotel rates'  =>  'View rates of hotels',
        'manage hotel rates' => 'add, update hotel rates',
        'manage hotel rooms' => 'add, update hotel rooms',
        'manage hotel services'=>'add, update hotel services'
     );
}

$hotelMainMenu = new MainMenuItem();
$hotelMainMenu->setId('MM_HOTEL');
$hotelMainMenu->setName('Hotels');
$hotelMainMenu->setIcon('fa-cutlery');
$hotelMainMenu->setRoute(site_url('hotel'));
$hotelMainMenu->setPermissions(array('administer hotel','view hotel', 'view hotel rate', 'manage hotel rates', 'manage hotel services'));
MainMenu::register($hotelMainMenu);


$hotelListMenu = new MainMenuItem();
$hotelListMenu->setId('MM_HOTEL_LIST');
$hotelListMenu->setName('List Hotels');
$hotelListMenu->setIcon('fa-list');
$hotelListMenu->setRoute(site_url('hotel'));
$hotelListMenu->setPermissions(array('view hotel'));
$hotelListMenu->setParent($hotelMainMenu);
MainMenu::register($hotelListMenu);


$hotelAddMenu = new MainMenuItem();
$hotelAddMenu->setId('MM_HOTEL_ADD');
$hotelAddMenu->setName('Add Hotel');
$hotelAddMenu->setIcon('fa-plus-square');
$hotelAddMenu->setRoute(site_url('hotel/add'));
$hotelAddMenu->setPermissions(array('administer hotel'));
$hotelAddMenu->setParent($hotelMainMenu);
MainMenu::register($hotelAddMenu);

$hotelCategoryMenu = new MainMenuItem();
$hotelCategoryMenu->setId('MM_HOTEL_CATEGORY');
$hotelCategoryMenu->setName('Hotel Category');
$hotelCategoryMenu->setIcon('fa-sitemap');
$hotelCategoryMenu->setRoute(site_url('hotel/category'));
$hotelCategoryMenu->setPermissions(array('administer hotel'));
$hotelCategoryMenu->setParent($hotelMainMenu);
MainMenu::register($hotelCategoryMenu);

$hotelGradeMenu = new MainMenuItem();
$hotelGradeMenu->setId('MM_HOTEL_GRADE');
$hotelGradeMenu->setName('Hotel Grade');
$hotelGradeMenu->setIcon('fa-star');
$hotelGradeMenu->setRoute(site_url('hotel/grade'));
$hotelGradeMenu->setPermissions(array('administer hotel'));
$hotelGradeMenu->setParent($hotelMainMenu);
MainMenu::register($hotelGradeMenu);

$hotelRoom = new MainMenuItem();
$hotelRoom->setId('MM_HOTEL_ROOM');
$hotelRoom->setName('Hotel Rooms');
$hotelRoom->setIcon('fa-home');
$hotelRoom->setRoute(site_url('hotel/room'));
$hotelRoom->setPermissions(array('manage hotel rooms'));
$hotelRoom->setParent($hotelMainMenu);
MainMenu::register($hotelRoom);

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

