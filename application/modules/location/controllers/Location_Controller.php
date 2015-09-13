<?php

class Location_Controller extends Admin_Controller{

    public function __construct(){
        parent::__construct();
        if( !user_access('administer location') ) redirect('dashboard');
        $this->breadcrumb->append_crumb('Location', site_url('location'));
        $this->load->helper('location/country');

    }

    public function index(){

        $countryRepo = $this->doctrine->em->getRepository('location\models\Country');

        $URIParams = getFiltersFromURL();

        $countries = $countryRepo->getCountryList($URIParams['offset'], PER_PAGE_DATA_COUNT, $URIParams['filters']);
        $total = count($countries);

        if($total > PER_PAGE_DATA_COUNT)
        {
            $this->templatedata['pagination'] = getPagination($total, 'location/index?'.$URIParams['param'], 2, TRUE);
        }

        $this->breadcrumb->append_crumb('country', site_url('location'));
        $this->templatedata['page_title'] = 'Countries';
        $this->templatedata['countries'] = &$countries;
        $this->templatedata['offset'] = $URIParams['offset'];
        $this->templatedata['post'] = $URIParams['post'];
        $this->templatedata['maincontent'] = 'location/country';

        $this->load->theme('master',$this->templatedata);
    }
}