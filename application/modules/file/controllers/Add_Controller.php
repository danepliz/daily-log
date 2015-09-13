<?php

class Add_Controller extends Admin_Controller
{
	public function __construct()
	{
		$this->mainmenu = MAINMENU_DASHBOARD;
		parent::__construct();
        $this->breadcrumb->append_crumb('Tour File', site_url('exchangeOrder'));
	}	
	
	public function index()
	{
        redirect('file/add/hotel');
//        $this->templatedata['page_title'] = 'Exchange Order || Hotel';
//		$this->templatedata['maincontent'] = 'exchangeOrder/hotel/hotel';
//		$this->load->theme('master',$this->templatedata);
	}

    public function hotel(){

        $this->breadcrumb->append_crumb('Add Hotel XO', site_url('file/add/hotel'));
        $this->templatedata['page_title'] = 'Add Hotel XO';
        $this->templatedata['maincontent'] = 'file/hotel/add_xo';
        $this->load->theme('master',$this->templatedata);
    }

}
?>