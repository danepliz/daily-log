<?php

function user_permissions()
{
	return array(	'view user' => 'list and view user'
                    ,'administer user'	=>	'Create, edit, block, unblock and delete users.'
					,'reset password'	=> "Reset other user's password."
					,'allow user switching' => 'User can run application as other user of same (or sub) agent.'
					);
}

$userMenu = new MainMenuItem();
$userMenu->setName('Users');
$userMenu->setId('MM_USER');
$userMenu->setPermissions(array('administer user'));
$userMenu->setRoute(current_url().'#');
$userMenu->setIcon('fa-users');
MainMenu::register($userMenu);

$listUserMenu = new MainMenuItem();
$listUserMenu->setName('List Users');
$listUserMenu->setId('MM_USER_LIST');
$listUserMenu->setParent($userMenu);
$listUserMenu->setIcon('fa-list');
$listUserMenu->setPermissions(array(
                                'view user',
								'administer user',
								'allow user switching', 
								'reset password',
							));
$listUserMenu->setRoute(site_url('user'));
\MainMenu::register($listUserMenu);

//$AddUserMenu = new MainMenuItem();
//$AddUserMenu->setName('Add User');
//$AddUserMenu->setId('MM_USER_ADD');
//$AddUserMenu->setParent($userMenu);
//$AddUserMenu->setIcon('fa-plus-square');
//$AddUserMenu->setPermissions(array(
//    'add user',
//));
//$AddUserMenu->setRoute(site_url('user/add'));
//\MainMenu::register($AddUserMenu);


if( Current_User::isSuperUser() ){
    $userGroupMenu = new MainMenuItem();
    $userGroupMenu->setName('User Groups');
    $userGroupMenu->setId('MM_USER_GROUPS');
    $userGroupMenu->setIcon('fa-group');
    $userGroupMenu->setParent($userMenu);
    $userGroupMenu->setRoute(site_url('user/group'));
    $userGroupMenu->setPermissions(array('manage user groups'));
    MainMenu::register($userGroupMenu);
}
//if( Current_User::isSuperUser() ) {
//    $AddUserGroupMenu = new MainMenuItem();
//    $AddUserGroupMenu->setName(' Add New User Group');
//    $AddUserGroupMenu->setId('MM_ADD_USER_GROUPS');
//    $AddUserGroupMenu->setIcon('fa-plus-square');
//    $AddUserGroupMenu->setParent($userMenu);
//    $AddUserGroupMenu->setRoute(site_url('user/addgroup'));
//    $AddUserGroupMenu->setPermissions(array('add user groups'));
//    MainMenu::register($AddUserGroupMenu);
//}