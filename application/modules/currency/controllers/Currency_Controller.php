<?php

use currency\models\Currency;

class Currency_Controller extends Admin_Controller{

    public function __construct(){
        parent::__construct();
        $this->breadcrumb->append_crumb('Currency', site_url('currency'));
    }

    public function index(){

        if(!user_access_or(['list currency', 'view currency', 'add currency', 'update currency']))	redirect('dashboard');

        $URIParams = getFiltersFromURL();
        $offset = $URIParams['offset'];
        $currencyRepository = $this->doctrine->em->getRepository('currency\models\Currency');
        $currencies = $currencyRepository->listCurrencies($offset, PER_PAGE_DATA_COUNT, $URIParams['filters']);
        $total = count($currencies);

        if($total > PER_PAGE_DATA_COUNT)
        {
            $this->templatedata['pagination']= getPagination($total, 'currency/index?'.$URIParams['param'], 2, TRUE);
        }

        $this->templatedata['counter'] = $offset ? $offset:0;
        $this->templatedata['currencies'] = & $currencies;
        $this->templatedata['offset'] = $offset;
        $this->templatedata['filters'] = $URIParams['filters'];
        $this->templatedata['post'] = $URIParams['post'];


        $this->templatedata['page_title'] = 'List Currency';
        $this->templatedata['maincontent'] = 'currency/list';
        $this->load->theme('master',$this->templatedata);
    }

    public function add(){

        if( !user_access('add currency')) redirect('dashboard');

        if( $this->input->post() ){

            $post = $this->input->post();

            $this->form_validation->set_rules('name', 'Currency Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('iso_3', 'ISO 3 Code', 'required|trim|xss_clean');

            if( $this->form_validation->run($this) === TRUE){

                $currentUser = Current_User::user();

                $currency = new Currency();
                $currency->setName(trim($post['name']));
                $currency->setDescription(trim($post['description']));
                $currency->setCreatedBy($currentUser);
                $currency->setIso3(trim($post['iso_3']));
                $currency->setSymbol(trim($post['symbol']));

                $this->doctrine->em->persist($currency);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Currency "'.$currency->getName().'" added successfully.', 'success', TRUE, 'feedback');
                }catch (\Exception $e){
                    $this->message->set('Unable to add Currency. "'.$e->getMessage().'"', 'success', TRUE, 'feedback');
                    $this->templatedata['has_error'] = TRUE;
                }
            }else{
                $this->templatedata['has_error'] = TRUE;
            }
        }
        redirect('currency');
    }


}