<?php
use \MainMenu;
use \MainMenuItem;
use \user\models\Group;
use \user\models\User;

function agent_permissions(){
        return array(
            'view agent'            =>	'list and View agents detail',
            'add agent'             =>	'Add agents',
            'edit agent'			=>	'Edit agents',
            'delete agent'			=>	'Delete agents',
            'view all agents'        => 'View all  agents',
            'view agent contact person' => 'list and view detail of agent contact person',
            'administer agent contact person' => 'Add, edit and delete contact persons of agents'
        );
}

// Agent Menu
$agentMainMenu = new MainMenuItem();
$agentMainMenu->setName('Agents');
$agentMainMenu->setId('MM_AGENTS');
$agentMainMenu->setPermissions(array('view agent', 'add agent'));
$agentMainMenu->setRoute(current_url().'#');
$agentMainMenu->setIcon('fa-user-secret');
MainMenu::register($agentMainMenu);

$agentListMenu = new MainMenuItem();
$agentListMenu->setName('List Agent');
$agentListMenu->setId('MM_LIST_AGENT');
$agentListMenu->setParent($agentMainMenu);
$agentListMenu->setIcon('fa-list');
$agentListMenu->setPermissions(array('view agent'));
$agentListMenu->setRoute(site_url('agent'));
MainMenu::register($agentListMenu);

$agentAddMenu = new MainMenuItem();
$agentAddMenu->setName('Add Agent');
$agentAddMenu->setId('MM_ADD_AGENT');
$agentAddMenu->setParent($agentMainMenu);
$agentAddMenu->setIcon('fa-plus-square');
$agentAddMenu->setPermissions(array('add agent'));
$agentAddMenu->setRoute(site_url('agent/add'));
MainMenu::register($agentAddMenu);

//if(!Current_User::isSuperUser() && user_access('view all agent'))
//{
//$agentViewAllMenu = new MainMenuItem();
//$agentViewAllMenu->setName('View all Agent');
//$agentViewAllMenu->setId('MM_VIEW_ALL_AGENT');
//$agentViewAllMenu->setParent($agentMainMenu);
//$agentViewAllMenu->setIcon('fa-list');
//$agentViewAllMenu->setPermissions(array('view all agent'));
//$agentViewAllMenu->setRoute(site_url('agent/listAll'));
//MainMenu::register($agentViewAllMenu);
//}





