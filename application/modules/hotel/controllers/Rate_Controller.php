<?php

use hotel\models\Rate;
use \hotel\models\HotelServiceRate;
use \hotel\models\HotelServiceRateDetail;
use hotel\models\Hotel;


class Rate_Controller extends Admin_Controller{

    public function __construct(){
        parent::__construct();

        $this->breadcrumb->append_crumb('Hotel', site_url('hotel'));
        $this->load->helper(array('hotel', 'location/country','sheepit'));
    }


    public function index(){

        if( !user_access('view hotel rates') ) redirect('dashboard');
        $params = getFiltersFromURL();
        $offset = $params['offset'];
        $filters = $params['filters'];
        $post = $params['post'];

        $rateRepository = $this->doctrine->em->getRepository('hotel\models\Rate');
        $rates = $rateRepository->listHotelRates($offset, PER_PAGE_DATA_COUNT, $filters);
        $ratesArray = array();
        $total = count($rates);


        $serviceRepository = $this->doctrine->em->getRepository('hotel\models\HotelServices');
        $services = $serviceRepository->listHotelServices($offset, PER_PAGE_DATA_COUNT, $filters);

        if($total > PER_PAGE_DATA_COUNT)
        {
            $this->templatedata['pagination']= getPagination($total, 'hotel/rate/index?'.$params['param'], 3, TRUE);
        }
        if( count($rates) > 0 ){
            foreach($rates as $rate){

                $hotelName = $rate['hotelName'];
                $marketName = ($rate['marketName'] == '') ? 'ANY' : $rate['marketName'];
                $plan = $rate['roomCategoryName'].' '.$rate['roomTypeName'].' ROOM ON '.$rate['roomPlanName'].' BASIS';
                $expiryDate = $rate['expiryDate'];

                $rateID = $rate['rate_id'];
                $currency = $rate['currency'];

                $ratesArray[$hotelName][$marketName][$rateID]['plan'] = str_replace('  ', ' ', trim($plan));
                $ratesArray[$hotelName][$marketName][$rateID]['expiryDate'] = ( $expiryDate ) ? $expiryDate->format('Y-m-d') : '';
                $ratesArray[$hotelName][$marketName][$rateID]['rates'][$currency] = [
                    'charge' => $rate['payableRate'],
                    'payableAmount' => $rate['payingAmount']
                ];
            }
        }
        $this->breadcrumb->append_crumb('Rates', site_url('hotel/rate'));
        $this->templatedata['page_title'] = 'Hotel Rates';
        $this->templatedata['rates'] = $ratesArray;
        $this->templatedata['services'] = $services;
        $this->templatedata['post'] = $post;
        $this->templatedata['maincontent'] = 'hotel/rates/list';
        $this->load->theme('master', $this->templatedata);
    }

    public function show($slug = ""){

        if( $slug == "" or !user_access('view hotel rates') ){ redirect('hotel'); }

        $hotelRepository = $this->doctrine->em->getRepository('hotel\models\Hotel');
        $hotel = $hotelRepository->findOneBy(array( 'slug' => $slug ));

        if( ! $hotel ){
            $this->message->set('Could not found hotel in our list.', 'error', TRUE, 'feedback');
            redirect('hotel');
        }



        if( $this->input->post() ){
            try{
                $adaptorClass = $this->input->post('adaptorClass');
                $postAdaptor = new $adaptorClass($hotel);
                $postAdaptor->updateRates($this->input->post());
                $this->message->set('Rates Updated Successfully.', 'success', TRUE, 'feedback');
            }catch(\Exception $e){
                $this->message->set('Unable To Update Rates. '.$e->getMessage(), 'error', TRUE, 'feedback');
            }

            redirect('hotel/rate/show/'.$slug);
        }

        $rateStrategy = $hotel->getRateVariationStrategy();

        $room_basis_seasonal_rates = [];
        $room_basis_non_seasonal_rates = [];
        $package_basis_seasonal_rates = [];
        $package_basis_non_seasonal_rates = [];
        $hotel_service_rates = [];

        if( $hotel->hasBookingTypePackageBasis() ){
            if( $rateStrategy == Hotel::HOTEL_RATE_VARIATION_STRATEGY_SEASONAL ){
                $detailAdaptor = new \Yarsha\HotelRates\PackageBasis\Seasonal($hotel);
                $package_basis_seasonal_rates = $detailAdaptor->getRates();
            }else{
                $detailAdaptor = new \Yarsha\HotelRates\PackageBasis\NonSeasonal($hotel);
                $package_basis_non_seasonal_rates = $detailAdaptor->getRates();
            }
        }

        if( $hotel->hasBookingTypeRoomBasis() ){
            if( $rateStrategy == Hotel::HOTEL_RATE_VARIATION_STRATEGY_SEASONAL ){
                $detailAdaptor = new \Yarsha\HotelRates\RoomBasis\Seasonal($hotel);
                $room_basis_seasonal_rates = $detailAdaptor->getRates();
            }else{
                $detailAdaptor = new \Yarsha\HotelRates\RoomBasis\NonSeasonal($hotel);
                $room_basis_non_seasonal_rates = $detailAdaptor->getRates();
            }
        }

        $serviceRateAdaptor = new \Yarsha\HotelRates\ServiceRate($hotel);
        $hotel_service_rates = $serviceRateAdaptor->getRates();

        $this->breadcrumb->append_crumb('Rate | '.$hotel->getName(), site_url('hotel/rate/show/'.$slug));
        $this->templatedata['page_title'] = 'Rate | '.$hotel->getName();
        $this->templatedata['maincontent'] = 'hotel/rates/tabs';
        $this->templatedata['hotel'] = $hotel;
        $this->templatedata['room_basis_seasonal_rates'] = $room_basis_seasonal_rates;
        $this->templatedata['room_basis_non_seasonal_rates'] = $room_basis_non_seasonal_rates;
        $this->templatedata['package_basis_seasonal_rates'] = $package_basis_seasonal_rates;
        $this->templatedata['package_basis_non_seasonal_rates'] = $package_basis_non_seasonal_rates;
        $this->templatedata['hotel_service_rates'] = $hotel_service_rates;
        $this->load->theme('master', $this->templatedata);


    }


}