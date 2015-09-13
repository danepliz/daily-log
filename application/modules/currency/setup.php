<?php

function currency_permissions(){
    return [
        'list currencies' => 'list all currencies',
        'add currency' => 'add new currency',
        'update currency'=> 'update particular currency',
        'view currency' => 'view detail of the particular currency'
    ];
}


$currencyMenu = new MainMenuItem();
$currencyMenu->setName('Currencies');
$currencyMenu->setIcon('fa-money');
$currencyMenu->setId('MM_CURRENCY');
$currencyMenu->setRoute(site_url('currency'));
$currencyMenu->setPermissions(['list currencies', 'view currency', 'add currency', 'update currency']);
MainMenu::register($currencyMenu);