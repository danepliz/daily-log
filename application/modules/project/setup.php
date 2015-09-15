<?php


function project_permissions(){
    return [
        'list project' => 'List projects',
        'view project' => 'View project detail',
        'edit project' => 'Edit project detail',
        'list project meta' => 'List projects meta data',
        'add project' => 'Add project',
        'delete project' => 'Delete Project'
    ];
}


$projectMainMenu = new MainMenuItem();
$projectMainMenu->setName('Projects');
$projectMainMenu->setIcon('fa-briefcase');
$projectMainMenu->setId('MM_PROJECT');
$projectMainMenu->setRoute(site_url('project'));
$projectMainMenu->setPermissions(['list project','view project', 'edit project', 'list project meta', 'add project','delete project']);
MainMenu::register($projectMainMenu);