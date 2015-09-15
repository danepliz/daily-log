<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');




class Project_Controller extends Admin_Controller{


    public function __construct(){
        parent::__construct();

        $this->breadcrumb->append_crumb('Project', site_url('project'));
    }


    public function index(){

        $projects = [];

        $this->templatedata['page_title'] = 'PROJECT | List';
        $this->templatedata['projects'] = $projects;
        $this->templatedata['maincontent'] = 'project/index';
        $this->load->theme('master', $this->templatedata);
    }




}






