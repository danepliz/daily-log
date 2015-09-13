<?php

function branch_permissions()
{
	return array(
        'administer branch'	=>	'Create, edit and delete branch.'
    );
}

$branchMainMenu = new MainMenuItem();
$branchMainMenu->setId('MM_BRANCH');
$branchMainMenu->setName('Branch');
$branchMainMenu->setIcon('fa-sitemap');
$branchMainMenu->setRoute(site_url('branch'));
$branchMainMenu->setPermissions(array('administer branch'));
MainMenu::register($branchMainMenu);
