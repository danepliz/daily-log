<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Config_Controller extends Admin_Controller {
	
	public function __construct()
	{
		$this->mainmenu = MAINMENU_SETTING;
		parent::__construct();
		
		$this->breadcrumb->append_crumb('Configuration', site_url('config'));
   }
	   
	   
	public function index()
	{
		$launchers = array();
		
		if (Current_User::isSuperUser()) {
		
			$launchers['CONFIG_LAUNCHER_USER_PERMISSIONS'] = 
				array(	'label'	=>	'User Roles',
						'route'	=>	'user/group',
						'launcher_icon'	=>	locateIcon('group'),
						'permission' => 'administer user'
						
					);
		}
		
		$launchers['CONFIG_LAUNCHER_TBSETTINGS'] =
		array(	'label'	=>	'Settings',
				'route'	=>	'config/settings',
				'launcher_icon'	=>	locateIcon('settings'),
				'permission' => 'general setting'
		);
		
		$launchersL = Events::trigger('config_launcher_init',$launchers,'array');
		
		$launcher = array();
		foreach($launchersL as $l){
			if(!array_key_exists('permission', $l) || (array_key_exists('permission', $l) && user_access($l['permission']))){
				$launcher[] = $l;
			}
		}
		$this->templatedata['launchers'] = $launcher;
		$this->templatedata['maincontent'] = 'config/config';
		$this->load->theme('master',$this->templatedata);
	}
	
}