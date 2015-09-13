<?php

use transport\models\Transport;
//use transport\models\transportMarket;
//use transport\models\transportPackage;
//use transport\models\transportSeason;



class Transport_Controller extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb->append_crumb('Transport', site_url('transport'));
        $this->load->helper(array('transport','sheepit'));
    }

    public function index()
    {

        if (!user_access('view transport')) {
            redirect('dashboard');
        }

        $params = getFiltersFromURL();
        $offset = $params['offset'];
        $filters = $params['filters'];
        $post = $params['post'];

        $transportRepository = $this->doctrine->em->getRepository('transport\models\Transport');

        $transports = $transportRepository->listTransports($offset, PER_PAGE_DATA_COUNT, $filters);

        $total = count($transports);

        if ($total > PER_PAGE_DATA_COUNT) {
            $this->templatedata['pagination'] = getPagination($total, 'transport/index?' . $params['param'], 2, TRUE);
        }

        $this->templatedata['page_title'] = 'Transport Lists';
        $this->templatedata['maincontent'] = 'transport/list';
        $this->templatedata['transports'] = $transports;
        $this->templatedata['post'] = $post;
        $this->templatedata['offset'] = $offset;
        $this->load->theme('master', $this->templatedata);
    }

    public function add()
    {
        if (!user_access('administer transport')) {
            redirect('dashboard');
        }

        if( $this->input->post() ){

            $post = $this->input->post();

            $this->form_validation->set_rules('name', 'Transport Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');

            if( $this->form_validation->run($this) === TRUE){

                $transport = new Transport();
//                $transport_category = $this->doctrine->em->find('transport\models\TransportCategory', $post['transport_category']);
//                $transport_grade = $this->doctrine->em->find('transport\models\TransportGrade', $post['transport_grade']);
//                $transport_country = $this->doctrine->em->find('location\models\Country', $post['country']);
                $transport_city = $post['city'];

                $transport_name = trim($post['name']);

                $phonesArray = array();
//                $emailsArray = array();

                if( isset($post['contact_phone']) and count($post['contact_phone']) > 0 ){
                    foreach($post['contact_phone'] as $pi => $phone){
                        if( $phone !== "" ) $phonesArray[] = $phone;
                    }
                }

//                if( isset($post['contact_email']) and count($post['contact_email']) > 0 ){
//                    foreach($post['contact_email'] as $ei => $email){
//                        if( $email !== "" ) $emailsArray[] = $email;
//                    }
//                }

                $transport->setName($transport_name);
                $transport->setAddress($post['address']);
//                $transport->setCategory($transport_category);
//                $transport->setCountry($transport_country);
                $transport->setCity($transport_city);
//                $transport->setEmails($emailsArray);
//                $transport->setFax($post['fax']);
//                $transport->setGrade($transport_grade);
//                $transport->setOthers($post['description']);
                $transport->setPhones($phonesArray);
//                $transport->setWebsite1(trim($post['website1']));
//                $transport->setWebsite2(trim($post['website2']));
//                $transport->setPaymentStrategy($post['payment_strategy']);
//                $transport->setPaymentStrategyPercent($post['strategy_percent']);

//                ($post['room_basis']!=null)?$transport->setHasBookingTypeRoomBasis(true):$transport->setHasBookingTypeRoomBasis(false);

//                ($post['package_basis']!=null)?$transport->setHasBookingTypePackageBasis(true):$transport->setHasBookingTypePackageBasis(false);
                if( $post['transport_status'] != Transport::TRANSPORT_STATUS_ACTIVE ){
                    $transport->setStatus($post['transport_status']);
                }


                $this->doctrine->em->persist($transport);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Transport "'.$transport_name.'" added successfully.', 'success', TRUE, 'feedback');
                    redirect('transport/detail/'.$transport->slug());

                }catch (\Exception $e){
                    $this->message->set('Unable to add transport. "'.$e->getMessage().'"', 'success', TRUE, 'feedback');
                }
            }
        }

        $this->templatedata['page_title'] = 'Transport Add';
        $this->templatedata['maincontent'] = 'transport/add';

        $this->load->theme('master', $this->templatedata);
    }

    public function detail($slug = ""){

        if(!user_access('view transport')){ redirect('dashboard'); }

        if( $slug == "" ){ redirect('transport'); }

        $transportRepository = $this->doctrine->em->getRepository('transport\models\Transport');
        $transport = $transportRepository->findOneBy(array( 'slug' => $slug ));

        if(! $transport ){ redirect('dashboard'); }

//        $seasonsRepo = $this->doctrine->em->getRepository('transport\models\TransportSeason');
//        $seasons= $seasonsRepo->findBy(array("transport"=>$transport->id(),"deleted"=>"FALSE"));

        if( ! $transport ){
            $this->message->set('Could not found transport in our list.', 'error', TRUE, 'feedback');
            redirect('transport');
        }

        $transportName = $transport->getName();
        $transportId = $transport->id();

//        $roomCategories = $transport->getRoomCategories();
//        $room_categories = array();
//        if( count($roomCategories) > 0 ){
//            foreach($roomCategories as $rc){
//                $room_categories[] = $rc->getName();
//            }
//        }

//        $roomTypes = $transport->getRoomTypes();
//        $room_types = array();
//        if( count($roomTypes) > 0 ){
//            foreach($roomTypes as $rt){
//                $room_types[] = $rt->getName();
//            }
//        }

//        $roomPlans = $transport->getRoomPlans();
//        $room_plans = array();
//        if( count($roomPlans) > 0 ){
//            foreach($roomPlans as $rp){
//                $room_plans[] = $rp->getName();
//            }
//        }

//        $payableCurrencies = $transport->getPayableCurrencies();
//        $payable_currencies = array();
//        if( count($payableCurrencies) > 0 ){
//            foreach($payableCurrencies as $pc){
//                $payable_currencies[] = $pc->getIso3();
//            }
//        }

        $URIParams = getFiltersFromURL();

        $offset = $URIParams['offset'];

//        $contactPersons = $transportRepository->listContactPerson(NULL, NULL, array('transport'=> $transportId));
//        $outletsrepo = $this->doctrine->em->getRepository('transport\models\transportOutlet');
//        $outlets = $outletsrepo->findBy(array('status'=>1,'transport'=> $transportId));
//
//        $servicesRepo = $this->doctrine->em->getRepository('transport\models\transportServices');
//        $services = $servicesRepo->findBy(array('status'=>1,'transport'=> $transportId));

        $this->breadcrumb->append_crumb(ucwords($transportName), site_url('transport/detail/'.$transportId));
        $this->templatedata['page_title'] = $transportName;
//        $this->templatedata['contactPersons'] = $contactPersons;
//        $this->templatedata['outlets'] = $outlets;

        $this->templatedata['transport'] = $transport;
//        $this->templatedata['transport_season'] = $seasons;
//        $this->templatedata['services'] = $services;
//        $this->templatedata['room_categories'] = $room_categories;
//        $this->templatedata['room_types'] = $room_types;
//        $this->templatedata['room_plans'] = $room_plans;
//        $this->templatedata['payable_currencies'] = $payable_currencies;
        $this->templatedata['maincontent'] = 'transport/detail';
        $this->load->theme('master', $this->templatedata);
    }
}