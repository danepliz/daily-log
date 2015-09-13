<?php

use hotel\models\Hotel;
 use hotel\models\HotelMarket;
use hotel\models\HotelPackage;
//use hotel\models\HotelSeason;



class Hotel_Controller extends Admin_Controller{

    public function __construct(){
        parent::__construct();

        $this->breadcrumb->append_crumb('Hotel', site_url('hotel'));
        $this->load->helper(array('hotel', 'location/country','sheepit'));
    }

    public function index(){

        if(!user_access('view hotel')){ redirect('dashboard'); }

        $params = getFiltersFromURL();
        $offset = $params['offset'];
        $filters = $params['filters'];
        $post = $params['post'];

        $hotelRepository = $this->doctrine->em->getRepository('hotel\models\Hotel');

        $hotels = $hotelRepository->listHotels($offset, PER_PAGE_DATA_COUNT, $filters);

        $total = count($hotels);

        if($total > PER_PAGE_DATA_COUNT)
        {
            $this->templatedata['pagination']= getPagination($total, 'hotel/index?'.$params['param'], 2, TRUE);
        }

        $this->templatedata['page_title'] = 'Hotel Lists';
        $this->templatedata['maincontent'] = 'hotel/list';
        $this->templatedata['hotels'] = $hotels;
        $this->templatedata['post'] = $post;
        $this->templatedata['offset'] = $offset;
        $this->load->theme('master', $this->templatedata);
    }

    public function add(){
        if(!user_access('administer hotel')){ redirect('dashboard'); }
        if( $this->input->post() ){

            $post = $this->input->post();

            $this->form_validation->set_rules('hotel_category', 'Hotel Category', 'required');
            $this->form_validation->set_rules('hotel_grade', 'Hotel Category', 'required');
            $this->form_validation->set_rules('name', 'Hotel Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('country', 'Country', 'required');
            $this->form_validation->set_rules('city', 'City', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');
            $this->form_validation->set_rules('payment_strategy', 'Payment Strategy', 'required');
            $this->form_validation->set_rules('strategy_percent', 'Payment Strategy Percent', 'required');
            $this->form_validation->set_rules('rate_variation','Rate Variation','required');
            $this->form_validation->set_rules('room_basis','Room Basis');
            $this->form_validation->set_rules('package_basis','Package Basis');
            if( $this->form_validation->run($this) === TRUE){

                $hotel = new Hotel();
                $hotel_category = $this->doctrine->em->find('hotel\models\HotelCategory', $post['hotel_category']);
                $hotel_grade = $this->doctrine->em->find('hotel\models\HotelGrade', $post['hotel_grade']);
                $hotel_country = $this->doctrine->em->find('location\models\Country', $post['country']);
                $hotel_city = $post['city'];
                $hotel_name = trim($post['name']);

                $phonesArray = array();
                $emailsArray = array();

                if( isset($post['contact_phone']) and count($post['contact_phone']) > 0 ){
                    foreach($post['contact_phone'] as $pi => $phone){
                        if( $phone !== "" ) $phonesArray[] = $phone;
                    }
                }

                if( isset($post['contact_email']) and count($post['contact_email']) > 0 ){
                    foreach($post['contact_email'] as $ei => $email){
                        if( $email !== "" ) $emailsArray[] = $email;
                    }
                }

                $hotel->setName($hotel_name);
                $hotel->setAddress($post['address']);
                $hotel->setCategory($hotel_category);
                $hotel->setCountry($hotel_country);
                $hotel->setCity($hotel_city);
                $hotel->setEmails($emailsArray);
                $hotel->setFax($post['fax']);
                $hotel->setGrade($hotel_grade);
                $hotel->setOthers($post['description']);
                $hotel->setPhones($phonesArray);
                $hotel->setWebsite1(trim($post['website1']));
                $hotel->setWebsite2(trim($post['website2']));
                $hotel->setPaymentStrategy($post['payment_strategy']);
                $hotel->setPaymentStrategyPercent($post['strategy_percent']);

                ($post['room_basis']!=null)?$hotel->setHasBookingTypeRoomBasis(true):$hotel->setHasBookingTypeRoomBasis(false);

                ($post['package_basis']!=null)?$hotel->setHasBookingTypePackageBasis(true):$hotel->setHasBookingTypePackageBasis(false);
                if( $post['hotel_status'] != HOTEL::HOTEL_STATUS_ACTIVE ){
                    $hotel->setStatus($post['hotel_status']);
                }


               $this->doctrine->em->persist($hotel);
                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Hotel "'.$hotel_name.'" added successfully.', 'success', TRUE, 'feedback');
                    redirect('hotel/detail/'.$hotel->slug());

                }catch (\Exception $e){
                    $this->message->set('Unable to add hotel. "'.$e->getMessage().'"', 'success', TRUE, 'feedback');
                }
            }
        }

        $this->breadcrumb->append_crumb('Add Hotel', site_url('hotel/add'));
        $this->templatedata['page_title'] = 'Add Hotel';
        $this->templatedata['maincontent'] = 'hotel/add';
        $this->load->theme('master', $this->templatedata);

    }

    public function detail($slug = ""){

        if(!user_access('view hotel')){ redirect('dashboard'); }

        if( $slug == "" ){ redirect('hotel'); }

        $hotelRepository = $this->doctrine->em->getRepository('hotel\models\Hotel');
        $hotel = $hotelRepository->findOneBy(array( 'slug' => $slug ));

        if(! $hotel ){ redirect('dashboard'); }

        $seasonsRepo = $this->doctrine->em->getRepository('hotel\models\HotelSeason');
        $seasons= $seasonsRepo->findBy(array("hotel"=>$hotel->id(),"deleted"=>"FALSE"));

        if( ! $hotel ){
            $this->message->set('Could not found hotel in our list.', 'error', TRUE, 'feedback');
            redirect('hotel');
        }

        $hotelName = $hotel->getName();
        $hotelId = $hotel->id();

        $roomCategories = $hotel->getRoomCategories();
        $room_categories = array();
        if( count($roomCategories) > 0 ){
            foreach($roomCategories as $rc){
                $room_categories[] = $rc->getName();
            }
        }

        $roomTypes = $hotel->getRoomTypes();
        $room_types = array();
        if( count($roomTypes) > 0 ){
            foreach($roomTypes as $rt){
                $room_types[] = $rt->getName();
            }
        }

        $roomPlans = $hotel->getRoomPlans();
        $room_plans = array();
        if( count($roomPlans) > 0 ){
            foreach($roomPlans as $rp){
                $room_plans[] = $rp->getName();
            }
        }

        $payableCurrencies = $hotel->getPayableCurrencies();
        $payable_currencies = array();
        if( count($payableCurrencies) > 0 ){
            foreach($payableCurrencies as $pc){
                $payable_currencies[] = $pc->getIso3();
            }
        }

        $URIParams = getFiltersFromURL();

        $offset = $URIParams['offset'];

        $contactPersons = $hotelRepository->listContactPerson(NULL, NULL, array('hotel'=> $hotelId));
        $outletsrepo = $this->doctrine->em->getRepository('hotel\models\HotelOutlet');
        $outlets = $outletsrepo->findBy(array('status'=>1,'hotel'=> $hotelId));

        $servicesRepo = $this->doctrine->em->getRepository('hotel\models\HotelServices');
        $services = $servicesRepo->findBy(array('status'=>1,'hotel'=> $hotelId));

        $this->breadcrumb->append_crumb(ucwords($hotelName), site_url('hotel/detail/'.$hotelId));
        $this->templatedata['page_title'] = $hotelName;
        $this->templatedata['contactPersons'] = $contactPersons;
        $this->templatedata['outlets'] = $outlets;

        $this->templatedata['hotel'] = $hotel;
        $this->templatedata['hotel_season'] = $seasons;
        $this->templatedata['services'] = $services;
        $this->templatedata['room_categories'] = $room_categories;
        $this->templatedata['room_types'] = $room_types;
        $this->templatedata['room_plans'] = $room_plans;
        $this->templatedata['payable_currencies'] = $payable_currencies;
        $this->templatedata['maincontent'] = 'hotel/detail';
        $this->load->theme('master', $this->templatedata);
    }

    public function edit($slug = ""){

        if( $slug == "" ||!user_access('administer hotel') ) redirect('dashboard');

        $hotelRepo = $this->doctrine->em->getRepository('hotel\models\Hotel');
        $hotel = $hotelRepo->findOneBy(array('slug' => $slug));

        if( is_null($hotel) ) redirect('hotel');

        if( $this->input->post() ){

            $post = $this->input->post();

            $this->form_validation->set_rules('hotel_category', 'Hotel Category', 'required');
            $this->form_validation->set_rules('hotel_grade', 'Hotel Category', 'required');
            $this->form_validation->set_rules('name', 'Hotel Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('hotel_status', 'Hotel Status', 'required');
            $this->form_validation->set_rules('country', 'Country', 'required');
            $this->form_validation->set_rules('city', 'City', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');
            $this->form_validation->set_rules('payment_strategy', 'Payment Strategy', 'required');
            $this->form_validation->set_rules('strategy_percent', 'Payment Strategy Percent', 'required');
            $this->form_validation->set_rules('room_basis','Room Basis');
            $this->form_validation->set_rules('package_basis','Package Basis');
            $this->form_validation->set_rules('rate_variation','Rate Variation','required');


            if($this->form_validation->run($this) === TRUE){

                if ($post['hotel_category'] !== $hotel->getCategory()->id()){
                    $hotel_category = $this->doctrine->em->find('hotel\models\HotelCategory', $post['hotel_category']);
                    $hotel->setCategory($hotel_category);
                }

                if ( $post['hotel_grade'] !== $hotel->getGrade()->id() ){
                    $hotel_grade = $this->doctrine->em->find('hotel\models\HotelGrade', $post['hotel_grade']);
                    $hotel->setGrade($hotel_grade);
                }

                if ( $post['country'] !== $hotel->getCountry()->id() ){
                    $country = $this->doctrine->em->find('location\models\Country', $post['country']);
                    $hotel->setCountry($country);
                }

                ($post['room_basis']!=null)?$hotel->setHasBookingTypeRoomBasis(true):$hotel->setHasBookingTypeRoomBasis(false);

                ($post['package_basis']!=null)?$hotel->setHasBookingTypePackageBasis(true):$hotel->setHasBookingTypePackageBasis(false);

                $phones = $post['contact_phone'];
                $phonesArray = array();
                $emails = $post['contact_email'];
                $emailsArray = array();

                if( count($phones) > 0 ){
                    foreach( $phones as $k => $v){
                        if( $v !== "" ) $phonesArray[] = $v;
                    }
                }

                if( count($emails) > 0 ){
                    foreach( $emails as $key => $val){
                        if( $val !== "" ) $emailsArray[] = $val;
                    }
                }

                $hotel->setName(trim($post['name']));
                $hotel->setCity(trim($post['city']));
                $hotel->setAddress(trim($post['address']));
                $hotel->setStatus($post['hotel_status']);
                $hotel->setOthers(trim($post['description']));
                $hotel->setWebsite1(trim($post['website1']));
                $hotel->setWebsite2(trim($post['website2']));
                $hotel->setPaymentStrategy($post['payment_strategy']);
                $hotel->setPaymentStrategyPercent($post['strategy_percent']);

                $hotel->setPhones($phonesArray);
                $hotel->setEmails($emailsArray);
                $hotel->setBookingType($post['booking']);
                $hotel->setRateVariationStrategy($post['rate_variation']);
                $this->doctrine->em->persist($hotel);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Hotel Updated Successfully', 'success', TRUE, 'feedback');
                    redirect('hotel/detail/'.$hotel->slug());
                }catch(\Exception $e){
                    $this->message->set('Unable to update hotel. '.$e->getMessage(), 'error', TRUE, 'feedback');
                }
            }
        }

        $this->breadcrumb->append_crumb('Edit', site_url('hotel/edit/'.$slug));
        $this->templatedata['page_title'] = 'Hotel Edit | '.$hotel->getName();
        $this->templatedata['hotel'] = $hotel;
        $this->templatedata['maincontent'] = 'hotel/edit';
        $this->load->theme('master', $this->templatedata);

    }

    public function updateRooms($hotelId)
    {

        $hotel = $this->doctrine->em->find('hotel\models\Hotel', $hotelId);

        if (is_null($hotel)) redirect('hotel');

        if( $this->input->post('updateRooms') ){

            $hotel->resetRoomCategories();
            if( $this->input->post('room_categories') ){
                $room_cat_repo = $this->doctrine->em->getRepository('hotel\models\HotelRoomCategory');
                foreach( $this->input->post('room_categories') as $rk => $rv ){
                    $room_cat = $room_cat_repo->find($rv);
                    $hotel->addRoomCategory($room_cat);
                }
            }

            $hotel->resetRoomTypes();
            if( $this->input->post('room_types') ){
                $room_type_repo = $this->doctrine->em->getRepository('hotel\models\HotelRoomType');
                foreach( $this->input->post('room_types') as $rk => $rv ){
                    $room_type = $room_type_repo->find($rv);
                    $hotel->addRoomType($room_type);
                }
            }

            $hotel->resetRoomPlans();
            if( $this->input->post('room_plans') ){
                $room_plan_repo = $this->doctrine->em->getRepository('hotel\models\HotelRoomPlan');
                foreach( $this->input->post('room_plans') as $rk => $rv ){
                    $room_plan = $room_plan_repo->find($rv);
                    $hotel->addRoomPlan($room_plan);
                }
            }

            $this->doctrine->em->persist($hotel);
            try{
                $this->doctrine->em->flush();
                $this->message->set('Hotel Rooms Updated Successfully', 'success', TRUE, 'feedback');
            }catch (\Exception $e){
                $this->message->set('Unable to update Hotel Rooms. '.$e->getMessage(), 'error', TRUE, 'feedback');
            }
        }

        redirect('hotel/detail/'.$hotel->slug().'?t=rooms');

    }

    public function updatePackages($hotelId){
        $hotel = $this->doctrine->em->find('hotel\models\Hotel', $hotelId);
        if( is_null($hotel) ) redirect('hotel');
        $slug = $hotel->slug();
        if( $this->input->post() ){
            $post = $this->input->post();
            $mainPackagesArray = $post['packages'];
            $extraPackagesArray = $post['extra'];
            $hotelPackageRepo = $this->doctrine->em->getRepository('hotel\models\HotelPackage');
            $isMainPackageDuplicate = FALSE;
            $isExtraPackageDuplicate = FALSE;
            $mainDuplicatedPackage = [];
            $extraDuplicatedPackage = [];
            $packagesArray = [];
            $numberOfNightsError = FALSE;
            $mpType = HotelPackage::PACKAGE_TYPE_MAIN;
            $epType = HotelPackage::PACKAGE_TYPE_EXTRA;

            foreach($mainPackagesArray as $mpk => $mpv){
                $name = trim($mpv['name']);
                if( $name != "" ){
                    if($mpv['nights'] <= 0 or $mpv['nights'] == '' or !is_numeric($mpv['nights'])){ $numberOfNightsError = TRUE; }
                    $package = ( $name != $mpv['old_name'])? $hotelPackageRepo->findOneBy([ 'name' => $name, 'type' => $mpType, 'hotel' => $hotelId ]) : NULL;
                    if($package){
                        $isMainPackageDuplicate = TRUE;
                        $mainDuplicatedPackage[] = $name;
                    }else{
                        $arr['name'] = $name;
                        $arr['desc'] = $mpv['description'];
                        $arr['type'] = $mpType;
                        $arr['id'] = $mpv['id'];
                        $arr['nights'] = $mpv['nights'];
                        $packagesArray[] = $arr;
                    }
                }
            }

            foreach($extraPackagesArray as $epk => $epv){

                    if($epv['nights'] <= 0 or $epv['nights'] == '' or !is_numeric($epv['nights'])){ $numberOfNightsError = TRUE; }
                $nameEx = trim($epv['name']);
                if( $nameEx != "" ){
                    $packageEx = ( $nameEx != $epv['old_name'])? $hotelPackageRepo->findOneBy([ 'name' => $nameEx, 'type' => $epType, 'hotel' => $hotelId ]) : NULL;
                    if($epv['nights'] <= 0 or $epv['nights'] == '' or !is_numeric($epv['nights'])){ $numberOfNightsError = TRUE; }
                    if($packageEx){
                        $isExtraPackageDuplicate = TRUE;
                        $extraDuplicatedPackage[] = $nameEx;
                    }else{
                        $arr['name'] = $nameEx;
                        $arr['desc'] = $epv['description'];
                        $arr['type'] = $epType;
                        $arr['id'] = $epv['id'];
                        $arr['nights'] = $epv['nights'];
                        $packagesArray[] = $arr;
                    }
                }
            }

            if( $isExtraPackageDuplicate or $isMainPackageDuplicate or $numberOfNightsError ){
                if($isMainPackageDuplicate){
                    $this->message->set('Packages "'.implode(',', $mainDuplicatedPackage).'" already exists. ', 'error', TRUE, 'feedback');
                }
                if($isExtraPackageDuplicate){
                    $this->message->set('Extras "'.implode(',', $extraDuplicatedPackage).'" already exists. ', 'error', TRUE, 'feedback');
                }
                if($numberOfNightsError){
                    $this->message->set('Number of nights must be numeric and greater than zero. ', 'error', TRUE, 'feedback');
                }
                redirect('hotel/detail/'.$slug.'?t=packages');
            }
            $hotel->resetPackages();
            if (count($packagesArray)) {
                foreach ($packagesArray as $mp) {
                    $mainPackage = (isset($mp['id']) and $mp['id'] != "") ? $hotelPackageRepo->find($mp['id']) : NULL;
                    if (is_null($mainPackage)) {
                        $mainPackage = new HotelPackage();
                    }
                    $mainPackage->setNumberOfNights($mp['nights']);
                    $mainPackage->setHotel($hotel);
                    $mainPackage->setName($mp['name']);
                    $mainPackage->setDescription($mp['desc']);
                    $mainPackage->setType($mp['type']);
                    $this->doctrine->em->persist($mainPackage);
                    $hotel->addPackage($mainPackage);
                }
            }
            $this->doctrine->em->persist($hotel);
            try{
                $this->doctrine->em->flush();
                $this->message->set('Packages Updated Successfully', 'success', TRUE, 'feedback');
            }catch(\Exception $e){
                $this->message->set('Unable to update packages. '.$e->getMessage(), 'error', TRUE, 'feedback');
            }
        }
        redirect('hotel/detail/'.$slug.'?t=packages');
    }



}