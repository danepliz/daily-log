<?php

class Dashboard_Controller extends Admin_Controller
{
	public function __construct()
	{
		$this->mainmenu = MAINMENU_DASHBOARD;
		parent::__construct();
//		$this->lang->load('dashboard');
	}	
	
	public function index()
	{
        $this->load->helper('dashboard/dashboard');
        $this->templatedata['page-title'] = 'Dashboard';
		$this->templatedata['maincontent'] = 'dashboard/dashboard';
		$this->load->theme('master',$this->templatedata);
		
	}
	
	public function pagenotfound(){
		$this->templatedata['maincontent'] = 'dashboard/error_404';
		$this->load->theme('master',$this->templatedata);
	}

}
?>