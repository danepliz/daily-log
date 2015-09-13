<?php

function file_permissions()
{
	return array(	'view tour file'	=>	'list and view details of tour file'
					,'manage tour file'	=> 'create and update tour file'
                    ,'view activities'  => 'list and view details of each activity'
					,'add activity' => 'Add activities'
                    ,'edit activity' => 'Edit activities'
                    ,'view exchange orders' => 'List exchange orders'
                    ,'view all exchange orders' => 'List all exchange orders'
                    ,'generate xo' => 'Generate Exchange Order of activities'
                    ,'revert xo' => 'Revert Exchange Order of activities'
                    ,'print xo' => 'Print Exchange Order'
                    ,'void xo' => 'void Exchange Order'
                    ,'email xo' => 'Email Exchange Order'
                    ,'view all tour files' => 'Able to view all tour files'
                    ,'view update margins' => 'View/Update Margins'
                    ,'view account copy'=> 'View Account Copy on print and email'
					);
}

$tourFileMainMenu = new MainMenuItem();
$tourFileMainMenu->setName('Tour Files');
$tourFileMainMenu->setIcon('fa-clipboard');
$tourFileMainMenu->setId('MM_TOUR_FILE');
$tourFileMainMenu->setRoute(site_url('file'));
$tourFileMainMenu->setPermissions(array('view tour file', 'manage tour file', 'view activities', 'add hotel activity'));
MainMenu::register($tourFileMainMenu);

$exchangeOrderFileMainMenu = new MainMenuItem();
$exchangeOrderFileMainMenu->setName('Exchange Order');
$exchangeOrderFileMainMenu->setIcon('fa-exchange');
$exchangeOrderFileMainMenu->setId('MM_EXCHANGE_ORDER_LIST');
$exchangeOrderFileMainMenu->setRoute(site_url('file/xo'));
$exchangeOrderFileMainMenu->setPermissions(array('view exchange orders'));
MainMenu::register($exchangeOrderFileMainMenu);

?>