<?php

//\Events::register('config_launcher_init', 'country_add_config_launcher');
//
//function country_add_config_launcher($launcher_list){
//	$launcher_list['CONFIG_LAUNCHER_COUNTRY']	=
//		array(	'label'	=>	'Countries',
//				'route'	=>	'location/country',
//				'launcher_icon'	=>	locateIcon('globe'),
//				'permission' => 'administer country'
//		);
//	return $launcher_list;
//}

function country_permissions()
{
	return array(
        'administer location'	=>	'Manage location information',
    );
}

$locationMainMenu = new MainMenuItem();
$locationMainMenu->setName('Countries');
$locationMainMenu->setIcon('fa-globe');
$locationMainMenu->setId('MM_LOCATION');
$locationMainMenu->setPermissions(array('administer location'));
$locationMainMenu->setRoute(site_url('location'));
MainMenu::register($locationMainMenu);


