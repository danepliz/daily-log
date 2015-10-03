<?php



class Task_Controller extends Admin_Controller{

    public function __construct(){
        parent::__construct();
    }

    public function index(){


        $this->templatedata['maincontent'] = 'task/index';
        $this->load->theme('master', $this->templatedata);
    }


}
