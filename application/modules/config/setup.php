<?php

function config_permissions(){
		return array(
            'general setting'			=>	'Update general settings.'
            ,'user setting'				=>	'Update user settings.'
        );
}

$settingsMainMenu = new MainMenuItem();
$settingsMainMenu->setName('Settings');
$settingsMainMenu->setId('MM_SETTINGS');
$settingsMainMenu->setPermissions(array('general setting', 'user setting'));
$settingsMainMenu->setRoute(site_url('config/settings'));
$settingsMainMenu->setIcon('fa-gears');
MainMenu::register($settingsMainMenu);


