<?php


class Market_Controller extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb->append_crumb('Market Category', site_url('market'));
        $this->load->helper('currency/currency');
    }

    public function index(){

        if( !user_access('view markets') ) redirect('dashboard');

        $marketRepository = $this->doctrine->em->getRepository('market\models\Market');
        $markets = $marketRepository->findAll();

        $this->breadcrumb->append_crumb('Category', site_url('market'));
        $this->templatedata['page_title'] = 'Market Category';
        $this->templatedata['hotel_grades'] = $markets;
        $this->templatedata['maincontent'] = 'market/list';
        $this->load->theme('master', $this->templatedata);
    }

    public function add()
    {

        if( !user_access('administer market') ) redirect('dashboard');

        if( $this->input->post() ){

            $this->form_validation->set_rules('name', 'Name', 'required|xss_clean|trim|callback_checkDuplicateMarket');
            $this->form_validation->set_rules('currency', 'Currency', 'required');

            $post = $this->input->post();

            if( $this->form_validation->run($this) === TRUE ){
                $marketName = strtoupper(trim($post['name']));
                $marketDescription = strtoupper(trim($post['description']));
                $currency = $this->doctrine->em->find('currency\models\Currency', $post['currency']);

                $market = new \market\models\Market();
                $market->setName($marketName);
                $market->setDescription($marketDescription);
                if($currency){
                    $market->setCurrency($currency);
                }

                $this->doctrine->em->persist($market);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('"'.$marketName.'" added successfully. ', 'success', TRUE, 'feedback');

                }catch (\Exception $e){
                    $this->message->set($e->getMessage(), 'error', TRUE, 'feedback');
                }
            }
            else{
                $validationError = validation_errors('<p>','</p>');
                $this->message->set($validationError, 'error', TRUE, 'feedback');
            }
        }

        redirect('market');
    }

    public function checkDuplicateMarket($str){
        $marketRepository = $this->doctrine->em->getRepository('market\models\Market');
        $market = $marketRepository->findOneBy(array('name' => $str));

        if( $market ){
            $this->form_validation->set_message('checkDuplicateMarket', 'Market Category "'.$str.'" already exists.');
            return FALSE;
        }

        return TRUE;
    }
}