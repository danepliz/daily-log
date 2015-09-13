<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Symfony\Component\EventDispatcher\EventDispatcher;

class MY_Controller extends MX_Controller
{
	
	//var $templatedata = array();
	var $_CONFIG ;
	
	//var $dbFilters = array();
	
	public function __construct()
	{
		parent::__construct();

		\CI::$APP->eventDispatcher = new EventDispatcher();
		
		// see if the options table exists
		//if not create it.
// 		if(!$this->db->table_exists($this->config->item('options_table')))
// 			$this->createOptionsTable();
//
// 		if(!$this->db->table_exists($this->config->item('sess_table_name')))
// 			$this->createSessionTable();
		
		System::themeOK();

		//parent::__construct();
		//$this->_CONFIG = Options::get('siteconfig');
		
		//get the current theme settings
		$current_theme = $this->config->item('current_theme');
		$template_path = './assets/themes/'.$current_theme.'/';

		//load the template definitions
		Modules::load_file('template'.EXT,$template_path);

		//initialize mainmenu
		\MainMenu::init();
		
		\OnlineMenu::init();

		foreach (Modules::$locations as $location => $offset)
		{		
			$dh = opendir($location);
			while($file = readdir($dh))
			{
				$path = $location.$file;
				if($file != "." AND $file != ".." AND is_dir($path))
				{
					$module = $file;
					if(file_exists($path."/setup.php"))
					{
						Modules::load_file("setup.php",$path.'/');
					}
				}
			}
		}
	}
}