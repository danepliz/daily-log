<?php

function market_permissions()
{
	return array(
        'view markets'	=>	'list and view markets.',
        'administer market'	=>	'Create, edit and delete market.'
    );
}

$marketMainMenu = new MainMenuItem();
$marketMainMenu->setId('MM_MARKET');
$marketMainMenu->setName('Market Category');
$marketMainMenu->setIcon('fa-building-o');
$marketMainMenu->setRoute(site_url('market'));
$marketMainMenu->setPermissions(array('view markets', 'administer market'));
MainMenu::register($marketMainMenu);
