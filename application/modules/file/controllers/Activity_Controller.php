<?php

use file\models\TourFileActivityHotel;
use file\models\TourFileActivityDetailHotel;
use Yarsha\Exception\YarshaException;
use hotel\models\Hotel;


class Activity_Controller extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file/xo');
        $this->breadcrumb->append_crumb('Tour File', site_url('exchangeOrder'));
    }

    public function index(){
        redirect('dashboard');
    }

    public function detail($activityID = '', $action = '')
    {
        if($activityID == "" or !user_access('view activities') ) redirect('dashboard');


        $activity = $this->doctrine->em->find('file\models\TourFileActivity', $activityID);

        try {
            if ($activity) {
                $print = ($action == '') ? FALSE : TRUE;
                $activityData = $this->yarsha->am->getActivityDetail($activity, $print);
                $mainContent=$activityData['mainContent'];
                $title = $activityData['title'];
                $logoLink = base_url().'assets/themes/yarsha/resources/images/brand.png';
                $stampLink = Options::get('config_stamp', '');
                $stampLocation = './'.substr(Options::get('config_stamp', ''), strlen(base_url()));
                $this->templatedata['logoSrc'] = $logoLink;
                $this->templatedata['stampSrc'] = $stampLink;

                $this->templatedata['page_title'] = $title;
                $this->templatedata['maincontent'] = $mainContent;
                $this->templatedata['data'] = $activityData;
                $this->load->theme('master', $this->templatedata);
            }
        } catch (Exception $e) {
            redirect('dashboard');
        }
    }


    public function getActivityForHotel($activity){

        $activityData = array();
        $activityDescription = array();

        $hotelName = 'N/A';
        $hotelContactPersonsEmails = array();
        $hotelEmails = array();

        $hotel = $activity->getHotel();

        if($hotel){
            $hotelName = $hotel->getName();
            $hotelEmails = $hotel->getEmails();

            if( count($hotelEmails) > 0 ){
                foreach($hotelEmails as $em){
                    $hotelContactPersonsEmails[] = $em;
                }
            }

            $hotelContactPersons = $hotel->getHotelContactPersons();
            if( count($hotelContactPersons) > 0 ){
                foreach($hotelContactPersons as $hp){
                    $emails = $hp->getEmails();
                    if( count($emails) > 0 ){
                        foreach($emails as $em){
                            $hotelContactPersonsEmails[] = $em;
                        }
                    }
                }
            }
        }

//        $activityData['emails']['hotel'] = $hotelEmails;
        $activityData['emails']['hotel'] = array_unique($hotelContactPersonsEmails);


        $activityData['hotel'] = strtoupper($hotelName);
        $activityData['nights'] = $activity->getNumberOfNights();
        $activityString = '';



        $activityDetails = $activity->getDetails();
        $arrivalDate = ( $activity->getArrivalDate() )? $activity->getArrivalDate()->format('Y-m-d') : '';
        $departureDate = ( $activity->getDepartureDate() )? $activity->getDepartureDate()->format('Y-m-d') : '';

        if( count($activityDetails) > 0 ){
            $count = 0;
            foreach($activityDetails as $ad){
                $totalRooms = $ad->getNumberOfRooms();
                $roomDesc = ($totalRooms == 1) ? ' ROOM' : ' ROOMS';
                $roomCategory = ($ad->getRoomCategory())? strtoupper($ad->getRoomCategory()->getName()) : '';
                $roomType = ($ad->getRoomType())? strtoupper($ad->getRoomType()->getName()).$roomDesc : $roomDesc;
                $roomPlan = ($ad->getRoomPlan()) ? $ad->getRoomPlan()->getName() : '';
                $res = array(
                    str_pad($totalRooms, 2, '0  ', STR_PAD_LEFT),
                    $roomCategory,
                    $roomType,
                    ' ON '.$roomPlan.' BASIS'
                );

                $rate = $ad->getHotelRate();
//                \Gedmo\DoctrineExtensions::Dump($payableRate);
                $payableRate = '00.00';
                $billingRate = '00.00';
                $specialRateApplied = FALSE;
                $rateId = '';

                $remarks = '';

                if( $rate ){
                    $rateId = $rate->id();
                    $payableRate = $rate->getPayableRate();
                    $billingRate = $rate->getBillingRate();
                }

                $totalAmount = $ad->getTotalAmount();

                if( $ad->isSpecialRateApplied() and $ad->getSpecialRate() !== "" ){
                    $totalAmount = $ad->getSpecialRate();
                    $specialRateApplied = TRUE;
                    $remarks = '[Special Rate Applied]. '.$ad->getReasonForSpecialRate();
                }

                if( $ad->isComplimentary() ){
                    $remarks .= ' [Complimentary]';
                }

                $note = $ad->getNote();
                $description = strtoupper(implode(' ',$res));
                if( $note ){
                    $description .= '( '.$note.' )';
                }

                $activityDescription[$count]['description'] = $description;
                $activityDescription[$count]['payableRate'] = number_format($payableRate, 2, '.', '');
                $activityDescription[$count]['billingRate'] = number_format($billingRate, 2, '.', '');
                $activityDescription[$count]['totalAmount'] = number_format($totalAmount, 2, '.', '');
                $activityDescription[$count]['remarks'] = strtoupper($remarks);
                $activityDescription[$count]['specialRateApplied'] = $specialRateApplied;
                $activityDescription[$count]['rateID'] = $rateId;

                $count++;
            }
        }else{
            $activityString = 'N/A';
        }

        $activityData['activityDescription'] = $activityString;
        $activityData['arrivalDate'] = $arrivalDate;
        $activityData['departureDate'] = $departureDate;
        $activityData['descriptions'] = $activityDescription;

        return $activityData;
    }

    public function generateXo($activityID = "")
    {
        if( $activityID == "" or !user_access('generate xo') ) redirect('dashboard');

        $activity = $this->doctrine->em->find('file\models\TourFileActivity', $activityID);

        if( !$activity ) redirect('dashboard');

        $currentUser = Current_User::user();

        $previousXoNumber = $activity->getXoNumber();
        if( is_null($previousXoNumber) or ! $previousXoNumber or $previousXoNumber == ''  ){
            $activityRepository = $this->doctrine->em->getRepository('file\models\TourFileActivity');
            $xoNumber = $activityRepository->getNextXoNumber();
            $activity->setXoNumber($xoNumber);
        }

        $activity->markAsXoGenerated();
        $activity->setUpdatedBy($currentUser);
        $activity->setXoCreatedDate(new DateTime());

        $activity->setXoGeneratedBy($currentUser);
        $this->doctrine->em->persist($activity);

        $fileID = $activity->getTourFile()->id();

        try{
            $this->doctrine->em->flush();
            $this->message->set('Exchange Order Generated Successfully.', 'success', TRUE, 'feedback');
            redirect('file/activity/detail/'.$activityID);
        }catch(\Exception $e){
            $this->message->set('Unable To Generate Exchange Order. '.$e->getMessage(), 'error', TRUE, 'feedback');
            redirect('file/detail/'.$fileID);
        }

    }


    public function hotel($fileID = '', $activityID = ''){

        if( $fileID == '' or !user_access('add activity') ) redirect('dashboard');

        $tourFile = $this->doctrine->em->find('file\models\TourFile', $fileID);

        if( ! $tourFile) redirect('dashboard');

        $tourActivity = ( $activityID and $activityID !== "" ) ? $this->doctrine->em->find('file\models\TourFileActivityHotel', $activityID) : NULL ;

        if( $tourActivity and $tourActivity->isXoGenerated() ){
            redirect('dashboard');
        }
        if( $this->input->post() ){

            $this->form_validation->set_rules('arrival_date', 'Arrival Date', 'required|trim|ys_date');
            $this->form_validation->set_rules('departure_date', 'Departure Date', 'required|trim|ys_date|date_compare[arrival_date]');
            $this->form_validation->set_rules('market', 'Market', 'required');
            $this->form_validation->set_rules('hotel', 'Hotel', 'required|trim|callback_validate_hotel_detail');

            if( $this->form_validation->run($this) === TRUE ){

                $post = $this->input->post();
                $details = $post['detail'];

                $arrivalDate = new DateTime($post['arrival_date']);
                $departureDate = new DateTime($post['departure_date']);
                $hotelID = $post['hotel'];
                $description = $post['description'];
                $mode_type = $post['mode_type'];
                $departureMode = $post['departure_mode'];
                $currentUser = Current_User::user();
                $estimatedTimeOfArrival = $post['estimatedArrivalTime'];
                $estimatedDepartureTime = $post['estimatedDepartureTime'];

                $confirmationNumber = $post['confirmationNumber'];
                $arrivalNote = $post['arrivalNote'];
                $departureNote = $post['departureNote'];
                $bookingType = $post['bookingType'];
                $hotel = $this->doctrine->em->find('hotel\models\Hotel', $hotelID);

                $dateDiff = date_diff($departureDate, $arrivalDate);
                $numberOfNights = $dateDiff->d;

                if( ! $tourActivity ){
                    $tourActivity = new TourFileActivityHotel();
                }

                $tourActivity->setCreatedBy($currentUser);
                $tourActivity->setArrivalDate($arrivalDate);
                $tourActivity->setDepartureDate($departureDate);
                $tourActivity->setTourFile($tourFile);
                $tourActivity->setDescription($description);
                $tourActivity->setArrivalMode($mode_type);
                $tourActivity->setHotel($hotel);
                $tourActivity->setConfirmationNumber($confirmationNumber);
                $tourActivity->setArrivalNote($arrivalNote);
                $tourActivity->setDepartureNote($departureNote);
                $tourActivity->setDepartureMode($departureMode);
                $tourActivity->setNumberOfNights($numberOfNights);
                $tourActivity->setBookingType($bookingType);

                $market = ( isset($post['market']) and $post['market'] != '' )? $this->doctrine->em->find('market\models\Market', $post['market']) : NULL;
                if( $market ){
                    $tourActivity->setMarket($market);
                }


                if( $estimatedTimeOfArrival !== '' ){
                    $arrivalTime = new DateTime($estimatedTimeOfArrival);
                    $tourActivity->setArrivalTime($arrivalTime);
                }

                if( $estimatedDepartureTime !== '' ){
                    $departureTime = new DateTime($estimatedDepartureTime);
                    $tourActivity->setDepartureTime($departureTime);
                }

                $this->doctrine->em->persist($tourActivity);

                /* Activity Details Persistence */
                if( count($details) > 0 ){
                    foreach($details as $k => $val){

                        $specialRateReason = $val['specialRateReason'];
                        $activityNote = $val['activityNote'];
                        $margin = $val['margin'];
                        $payableAmount = $val['payableAmount'];
                        $billingAmount = $val['billingAmount'];

                        $isComplimentary = ( isset($val['complimentary']) and $val['complimentary'] == TRUE ) ? TRUE : FALSE;
                        $isSpecialRateApplied = ( isset($val['applySpecialRate']) and $val['applySpecialRate'] == TRUE) ? TRUE : FALSE;

                        $tourFileActivityDetail = ($val['activityDetailID'] !== "")? $this->doctrine->em->find('file\models\TourFileActivityDetail', $val['activityDetailID']) : new TourFileActivityDetailHotel();

                        if( $bookingType == Hotel::HOTEL_BOOKING_TYPE_ROOM_BASIS ){

                            $category = $this->doctrine->em->find('hotel\models\HotelRoomCategory', $val['category']);
                            $type = $this->doctrine->em->find('hotel\models\HotelRoomType', $val['type']);
                            $plan = $this->doctrine->em->find('hotel\models\HotelRoomPlan', $val['plan']);
                            $roomTypeNickName = ( isset($val['roomTypeNickName']) )? $val['roomTypeNickName'] : '';

                            $tourFileActivityDetail->setRoomCategory($category);
                            $tourFileActivityDetail->setRoomPlan($plan);
                            $tourFileActivityDetail->setRoomType($type);
                            $tourFileActivityDetail->setNickNameForRoomType($roomTypeNickName);
                            $tourFileActivityDetail->setExtraBed($val['extraBed']);

                        }else{
                            $package = $this->doctrine->em->find('hotel\models\HotelPackage', $val['package']);
                            $tourFileActivityDetail->setPackage($package);

                            $extra = $val['extra'];
                            if( count($extra) ){
                                foreach($extra as $ek => $ev ){

                                    if( $ev['package'] == "" ) continue;

                                    $tourFileActivityExtraDetail = ($ev['activityDetailID'] !== "")? $this->doctrine->em->find('file\models\TourFileActivityDetail', $ev['activityDetailID']) : new TourFileActivityDetailHotel();
                                    $ePackage = $this->doctrine->em->find('hotel\models\HotelPackage', $ev['package']);
                                    $tourFileActivityExtraDetail->setPackage($ePackage);

                                    $eMargin = ( $ev['margin'] != "" )? $ev['margin'] : NULL;
                                    $tourFileActivityExtraDetail->setMargin($eMargin);

                                    $extraAppliedRate = $this->doctrine->em->find('hotel\models\Rate', $ev['actualRate']);
                                    $extraRateDetail = $this->doctrine->em->find('hotel\models\RateDetail', $ev['actualRateDetail']);

                                    if( $extraAppliedRate ) $tourFileActivityExtraDetail->setHotelRate($extraAppliedRate);
                                    if( $extraRateDetail ) $tourFileActivityExtraDetail->setHotelRateDetail($extraRateDetail);

                                    $tourFileActivityExtraDetail->setNumberOfRooms($val['quantity']);
                                    $tourFileActivityExtraDetail->setPayableAmount($ev['payableAmount']);
                                    $tourFileActivityExtraDetail->setBillingAmount($ev['billingAmount']);
                                    $tourFileActivityExtraDetail->setTotalAmount($val['billingAmount']);
                                    $tourFileActivityExtraDetail->setPaymentStrategyType($ev['paymentStrategy']);
                                    $tourFileActivityExtraDetail->setPaymentStrategyPercent($ev['paymentStrategyPercent']);
                                    $tourFileActivityExtraDetail->setParent($tourFileActivityDetail);
                                    if($isComplimentary){
                                        $tourFileActivityExtraDetail->markAsComplimentary();
                                    }else{
                                        $tourFileActivityExtraDetail->unMarkAsComplimentary();
                                    }

                                    $this->doctrine->em->persist($tourFileActivityExtraDetail);

                                }
                            }
                        }

                        $appliedRate = $this->doctrine->em->find('hotel\models\Rate', $val['actualRate']);
                        $rateDetail = $this->doctrine->em->find('hotel\models\RateDetail', $val['actualRateDetail']);

                        if( $appliedRate ) $tourFileActivityDetail->setHotelRate($appliedRate);
                        if( $rateDetail ) $tourFileActivityDetail->setHotelRateDetail($rateDetail);

                        if( $isSpecialRateApplied ){
                            $tourFileActivityDetail->markAsSpecialRateApplied();
                            $tourFileActivityDetail->setReasonForSpecialRate($specialRateReason);
                            $payableAmount = $val['specialPaymentAmount'];
                            $billingAmount = $val['specialBillingAmount'];
                            $margin = $val['specialMargin'];
                        }
                        else{
                            $tourFileActivityDetail->markAsSpecialRateNotApplied();
                        }

                        $tourFileActivityDetail->setNumberOfRooms($val['quantity']);
                        $tourFileActivityDetail->setTourActivity($tourActivity);
                        if($isComplimentary){
                            $tourFileActivityDetail->markAsComplimentary();
                        }else{
                            $tourFileActivityDetail->unMarkAsComplimentary();
                        }
                        $tourFileActivityDetail->setTotalAmount($billingAmount);
                        $tourFileActivityDetail->setNote($activityNote);
                        if( $margin != '' ){
                            $tourFileActivityDetail->setMargin($margin);
                        }else{
                            $tourFileActivityDetail->setMargin(NULL);
                        }
                        $tourFileActivityDetail->setPayableAmount($payableAmount);
                        $tourFileActivityDetail->setBillingAmount($billingAmount);
                        $tourFileActivityDetail->setPaymentStrategyType($val['paymentStrategy']);
                        $tourFileActivityDetail->setPaymentStrategyPercent($val['paymentStrategyPercent']);

                        $tourActivity->addDetail($tourFileActivityDetail);
                        $this->doctrine->em->persist($tourFileActivityDetail);

                    }
                }


                try{
                    $this->doctrine->em->flush();
                    $msg = ( $activityID == '' )? 'Hotel Activity Added Successfully.' : 'Hotel Activity Updated Successfully.';
                    $this->message->set($msg, 'success', TRUE, 'feedback');
                    redirect('file/detail/'.$fileID);
                }catch(\Exception $e){
                    $this->message->set($e->getMessage(), 'error', TRUE, 'feedback');
                    $url = ( $activityID == '' )? 'file/activity/hotel/'.$fileID : 'file/activity/hotel/'.$fileID.'/'.$activityID;
                    redirect($url);
                }
            }
        }

        $tourFileActivityDetailRepo = $this->doctrine->em->getRepository('file\models\TourFileActivityDetail');
        $tourFileActivityDetails = $tourFileActivityDetailRepo->getActivityDetailsByActivityType($activityID);
        $this->load->helper('hotel/hotel');
        $this->templatedata['activity_details'] =  $tourFileActivityDetails;
        $this->templatedata['page_title'] = 'Hotel Activity';
        $this->templatedata['file'] = $tourFile;
        $this->templatedata['activity'] = $tourActivity;
        $this->templatedata['maincontent'] = 'file/activities/hotel_activity';
        $this->load->theme('master', $this->templatedata);

    }


    public function deleteActivity(){

        $hotel_activity_Id = $this->input->post('id');

        $hotel_activity = $this->doctrine->em->find('file\models\TourFileActivity', $hotel_activity_Id);
        $response['status'] = 'error';
        $response['message'] = '';
//        die('sdada');
        if( $hotel_activity ){

            $hotel_activity->markAsDeleted();
            $this->doctrine->em->persist($hotel_activity);

            try {
                $this->doctrine->em->flush();
                log_message('info', 'HotelActivity marked as deleted');
                $response['status'] = 'success';
                $response['message'] = 'The HotelActivity has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to delete HotelActivity. '.$e->getMessage();
            }
        }else{
            $response['message'] = 'HotelActivity Not Found.';
        }

        echo json_encode($response);

    }


    public function validate_hotel_detail($val){
        $details = $this->input->post('detail');
        $allowedBeds = $this->input->post('allowedBeds');

        $hasSpecialRateError = FALSE;
        $hasRateError = FALSE;
        $hasQtyError = FALSE;
        $hasRoomTypeError = FALSE;
        $hasBedExceedError = FALSE;
        $mainPackageError = FALSE;

        $totalBedsBooked = 0;

        $bookingType = $this->input->post('bookingType');


        if( count($details) > 0 ){

            foreach($details as $key => $value){

                if( ! isset($value['quantity']) or $value['quantity'] == '' ){ $hasQtyError = TRUE; }

                if( $bookingType == Hotel::HOTEL_BOOKING_TYPE_PACKAGE_BASIS ){
                    if( !isset($value['package']) or $value['package'] == '' ){
                        $mainPackageError = TRUE;
                    }
                }else{
                    $hotelRoomType = ( isset($value['type']) and $value['type'] !== '' and is_numeric($value['type']) )? $this->doctrine->em->find('hotel\models\HotelRoomType', $value['type']) : NULL;

                    if( $hotelRoomType ){
                        $beds = $hotelRoomType->getQuantity();
                        $totalBeds = $beds * $value['quantity'];
                        $totalBedsBooked += $totalBeds;

                    }else{
                        $hasRoomTypeError = TRUE;
                    }

//                    if(! isset($value['actualRate']) or $value['actualRate'] == '' or ! is_numeric($value['actualRate'])){
//                        $hasRateError = TRUE;
//                    }

                    if( isset($value['applySpecialRate']) and $value['applySpecialRate'] == TRUE ){
                        if(  $value['specialPaymentAmount'] == "" ){  $hasSpecialRateError = TRUE; }
                    }else{

                        if(! isset( $value['complimentary'] ) or $value['complimentary'] == false){
                            if( ! isset($value['actualRate']) or $value['actualRate'] == '' or ! is_numeric($value['actualRate']) ){
                                $hasRateError = TRUE;
                            }
                        }

//                        if( ! isset($value['actualRate']) or $value['actualRate'] == '' or ! is_numeric($value['actualRate']) ){
//                            if( ! isset( $value['complimentary'] ) or $value['complimentary'] == false ){
//                                if( ! isset($value['specialRate']) or $value['specialRate'] == '' or !is_numeric($value['specialRate']) ){
//                                    $hasRateError = TRUE;
//                                }
//                            }
//                        }
                    }



//                    if( isset($value['applySpecialRate']) and $value['applySpecialRate'] == TRUE ){
//                        if(  $value['specialPaymentAmount'] == "" ){  $hasSpecialRateError = TRUE; }
//                    }
                }

            }
        }

        $errorMessage = array();
//        $errorMessage[] = 'Test success';

        if( $totalBedsBooked > $allowedBeds  ){
            $hasBedExceedError = TRUE;
            $errorMessage[] = 'Number of rooms exceeds the size of Pax.';
        }
        if( $hasRoomTypeError ){ $errorMessage[] = 'Please specify the room types'; }
        if( $hasQtyError ){ $errorMessage[] = 'Number of rooms must be defined for every detail.'; }
        if( $hasRateError ){ $errorMessage[] = 'Detail must be complimentary or Special rate must be applied if Hotel Rate is not defined.'; }
        if( $hasSpecialRateError ){ $errorMessage[] = 'Be sure that payable amount is defined for special rate, if checked Apply special rate.'; }
        if( $mainPackageError ){ $errorMessage[] = 'Please Specify the Package Type.'; }

//        $this->form_validation->set_message('validate_hotel_detail', implode('<br />', $errorMessage));
//        return false;

        if($hasRateError or $hasQtyError or $hasSpecialRateError or $hasBedExceedError or $hasRoomTypeError or $mainPackageError){
            $this->form_validation->set_message('validate_hotel_detail', implode('<br />', $errorMessage));
            return false;
        }
        else{
            return true;
        }
    }

    public function deleteHotelActivity(){
        $hotel_activity_Id = $this->input->post('id');

        $hotel_activity = $this->doctrine->em->find('file\models\TourFileActivityDetail', $hotel_activity_Id);
        $response['status'] = 'error';
        $response['message'] = '';

        if( $hotel_activity ){
            $hotel_activity->markAsDeleted();
            $this->doctrine->em->persist($hotel_activity);

            try {
                $this->doctrine->em->flush();
                log_message('info', 'HotelActivityDetail marked as deleted');
                $response['status'] = 'success';
                $response['message'] = 'The HotelActivityDetail has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to delete HotelActivityDetail. '.$e->getMessage();
            }
        }else{
            $response['message'] = 'HotelActivityDetail Not Found.';
        }

        echo json_encode($response);
    }


    public function email(){

        if( $this->input->post() ){

            $post = $this->input->post();

            $activityID = $post['activityID'];

            if( $activityID == '' ) redirect('dashboard');

            $activity = $this->doctrine->em->find('file\models\TourFileActivity', $activityID);

            if( ! $activity ) redirect('dashboard');

            $emails = $this->input->post('emails');

            $emailCount = 0;
            $clientsEmail = array();

            if( isset($emails['agent']) and count($emails['agent']) > 0 ){
                $emailCount++;
            }

            if( isset($emails['hotel']) and count($emails['hotel']) > 0 ){
                $emailCount++;
            }

            if( isset($emails['account']) and count($emails['account']) > 0 ){
                $emailCount++;
            }

            if( isset($emails['client']) and count($emails['client']) > 0 ){
                foreach($emails['client'] as $cem){
                    if( $cem !== "" ){
                        $clientsEmail[] = $cem;
                        $emailCount++;
                    }
                }
            }

            $emails['client'] = $clientsEmail;

            if( $emailCount == 0){
                $this->message->set('Please select atleast one email before sending.', 'error', true, 'feedback');
                redirect('file/activity/detail/'.$activityID);
            }

            $activityData = array();

            try{
                $activityData = $this->yarsha->am->getActivityDetail($activity, TRUE);

            }catch(YarshaException $ye){
                $this->message->set($ye->getMessage(), 'error', true, 'feedback');
                redirect('file/activity/detail/'.$activityID);
            }

            $receivers = $activityData['emailReceivers'];
            $activityType = $activityData['activityType'];
            $xo = strtoupper($activityData['xoNumber']);

            $this->templatedata['data'] = $activityData;
            $logoLink = base_url().'assets/themes/yarsha/resources/images/brand.png';
            $stampLink = Options::get('config_stamp', '');
            $stampLocation = './'.substr(Options::get('config_stamp', ''), strlen(base_url()));
            $this->templatedata['logoSrc'] = $logoLink;
            $this->templatedata['stampSrc'] = $stampLink;

            $emailDetails = array();


            foreach( $receivers as $rc ){

                if( isset($emails[$rc]) and count($emails[$rc]) > 0 ){
                    $this->templatedata['emailFor'] = $rc;
                    $emailBody = $this->load->theme('file/print/'.$activityType.'_xo', $this->templatedata, TRUE);

                    $this->load->library('dompdf_gen');
                    $dompdf = new DOMPDF();
                    $this->templatedata['logoSrc'] = './assets/themes/yarsha/resources/images/brand.png';
                    $this->templatedata['stampSrc'] = $stampLocation;
                    $dompdf->load_html($this->load->theme('file/print/'.$activityType.'_xo', $this->templatedata, TRUE));
                    $dompdf->render();
                    $output = $dompdf->output();
                    $file_to_save = './assets/uploads/to/'.$xo.'.pdf';
                    file_put_contents($file_to_save, $output);
                    $this->templatedata['logoSrc'] = $logoLink;
                    $this->templatedata['stampSrc'] = $stampLink;


                    $emailDetails[] = array(
                        'for' => $rc,
                        'to' => implode(',', $emails[$rc]),
                        'subject' => ucfirst($rc).' Exchange Order',
                        'message' =>  $emailBody,
                    );
                }
            }

            if( isset($emails['client']) and count($emails['client']) > 0 ){
                $clientsEmail = array();
                foreach($emails['client'] as $cem){
                    if( $cem !== "" ){
                        $clientsEmail[] = $cem;
                    }
                }
            }

            $emailError = FALSE;
            $errorMessage = array();
            if( count($emailDetails) > 0 ){

                $this->load->library('email');

                foreach($emailDetails as $detail){
                    $this->email->to($detail['to']);
                    $this->email->from('noreply@yetibilling.com', "Yetibilling");
                    $this->email->subject($detail['subject']);
                    $this->email->message($detail['message']);
                    $this->email->attach('./assets/uploads/to/'.$xo.'.pdf');

                    if( ! $this->email->send() ){
                        log_message('error', $this->email->print_debugger());
                        $emailError = TRUE;
                        $errorMessage[] = $detail['for'];
                    }
                }
            }

            if( $emailError ){
                $this->message->set('Unable to send emails for '.implode(', ', $errorMessage), 'error', TRUE, 'feedback');
            }else{
                $this->message->set('Emails send successfully.', 'success', TRUE, 'feedback');
                unlink(FCPATH .'assets/uploads/to/'.$xo.'.pdf');
            }

            redirect('file/activity/detail/'.$activityID);
        }else{
            redirect('dashboard');
        }


    }
    
    public function service($fileID = '', $activityID = ''){
        if( $fileID == '' or !user_access('add activity') ) redirect('dashboard');

        $tourFile = $this->doctrine->em->find('file\models\TourFile', $fileID);

        $serviceActivity = ( $tourFile and $activityID and $activityID !== "" ) ? $this->doctrine->em->find('file\models\TourFileActivityHotel', $activityID) : NULL ;

        if( $serviceActivity and $serviceActivity->isXoGenerated() ){
            redirect('dashboard');
        }
        if( $this->input->post() ){

            $this->form_validation->set_rules('arrival_date', 'Arrival Date', 'required|trim|ys_date');
            $this->form_validation->set_rules('market', 'Market', 'required');
            $this->form_validation->set_rules('hotel', 'Provider', 'required|trim|callback_validate_hotel_service_detail');

            if( $this->form_validation->run($this) === TRUE ){

                $post = $this->input->post();
                $details = $post['detail'];

                $arrivalDate = new DateTime($post['arrival_date']);
                $hotelID = $post['hotel'];
                $description = $post['description'];
                $mode_type = $post['mode_type'];
                $currentUser = Current_User::user();
                $estimatedTimeOfArrival = $post['estimatedArrivalTime'];

                $confirmationNumber = $post['confirmationNumber'];
                $arrivalNote = $post['arrivalNote'];
                $serviceType = $post['serviceType'];
                $hotel = $this->doctrine->em->find('hotel\models\Hotel', $hotelID);

                if( ! $serviceActivity ){
                    $serviceActivity = new TourFileActivityHotel();
                }

                $today = new DateTime();

                $serviceActivity->setCreatedBy($currentUser);
                $serviceActivity->setArrivalDate($arrivalDate);
                $serviceActivity->setDepartureDate($today);
                $serviceActivity->setTourFile($tourFile);
                $serviceActivity->setDescription($description);
                $serviceActivity->setArrivalMode($mode_type);
                $serviceActivity->setHotel($hotel);
                $serviceActivity->setConfirmationNumber($confirmationNumber);
                $serviceActivity->setArrivalNote($arrivalNote);
                $serviceActivity->setBookingType(Hotel::HOTEL_BOOKING_TYPE_SERVICE_BASIS);
                $market = (isset($post['market']) and $post['market']!= '')? $this->doctrine->em->find('market\models\Market', $post['market']) : NULL;
                if( $market ){
                    $serviceActivity->setMarket($market);
                }


                if( $estimatedTimeOfArrival !== '' ){
                    $arrivalTime = new DateTime($estimatedTimeOfArrival);
                    $serviceActivity->setArrivalTime($arrivalTime);
                }

                $this->doctrine->em->persist($serviceActivity);

                /* Activity Details Persistence */
                if( count($details) > 0 ){
                    foreach($details as $k => $val){

                        $specialRateReason = $val['specialRateReason'];
                        $activityNote = $val['activityNote'];
                        $margin = $val['margin'];
                        $payableAmount = $val['payableAmount'];
                        $billingAmount = $val['billingAmount'];

                        $isComplimentary = ( isset($val['complimentary']) and $val['complimentary'] == TRUE ) ? TRUE : FALSE;
                        $isSpecialRateApplied = ( isset($val['applySpecialRate']) and $val['applySpecialRate'] == TRUE) ? TRUE : FALSE;

                        $tourFileActivityDetail = ($val['activityDetailID'] !== "")? $this->doctrine->em->find('file\models\TourFileActivityDetail', $val['activityDetailID']) : new TourFileActivityDetailHotel();

                        $outlet = ($val['outlet'] and $val['outlet'] != '') ?  $this->doctrine->em->find('hotel\models\HotelOutlet', $val['outlet']) : NULL;
                        $service = ($val['service'] and $val['service'] != '') ?  $this->doctrine->em->find('hotel\models\HotelServices', $val['service']) : NULL;
                        if( $outlet ){
                            $tourFileActivityDetail->setOutlet($outlet);
                        }
                        if( $service ){
                            $tourFileActivityDetail->setService($service);
                        }

                        $appliedRate = $this->doctrine->em->find('hotel\models\Rate', $val['actualRate']);
                        $rateDetail = $this->doctrine->em->find('hotel\models\RateDetail', $val['actualRateDetail']);

                        if( $appliedRate ) $tourFileActivityDetail->setHotelRate($appliedRate);
                        if( $rateDetail ) $tourFileActivityDetail->setHotelRateDetail($rateDetail);

                        if( $isSpecialRateApplied ){
                            $tourFileActivityDetail->markAsSpecialRateApplied();
                            $tourFileActivityDetail->setReasonForSpecialRate($specialRateReason);
                            $payableAmount = $val['specialPaymentAmount'];
                            $billingAmount = $val['specialBillingAmount'];
                            $margin = $val['specialMargin'];
                        }
                        else{
                            $tourFileActivityDetail->markAsSpecialRateNotApplied();
                        }

                        $tourFileActivityDetail->setNumberOfRooms($val['quantity']);
                        $tourFileActivityDetail->setTourActivity($serviceActivity);
                        if($isComplimentary){
                            $tourFileActivityDetail->markAsComplimentary();
                        }else{
                            $tourFileActivityDetail->unMarkAsComplimentary();
                        }
                        $tourFileActivityDetail->setTotalAmount($billingAmount);
                        $tourFileActivityDetail->setNote($activityNote);
                        if( $margin != '' ){
                            $tourFileActivityDetail->setMargin($margin);
                        }else{
                            $tourFileActivityDetail->setMargin(NULL);
                        }
                        $tourFileActivityDetail->setPayableAmount($payableAmount);
                        $tourFileActivityDetail->setBillingAmount($billingAmount);
                        $tourFileActivityDetail->setPaymentStrategyType($val['paymentStrategy']);
                        $tourFileActivityDetail->setPaymentStrategyPercent($val['paymentStrategyPercent']);

                        $serviceActivity->addDetail($tourFileActivityDetail);
                        $this->doctrine->em->persist($tourFileActivityDetail);

                    }
                }

                try{
                    $this->doctrine->em->flush();
                    $msg = ( $activityID == '' )? 'Hotel Activity Added Successfully.' : 'Hotel Activity Updated Successfully.';
                    $this->message->set($msg, 'success', TRUE, 'feedback');
                    redirect('file/detail/'.$fileID);
                }catch(\Exception $e){
                    $this->message->set($e->getMessage(), 'error', TRUE, 'feedback');
                    $url = ( $activityID == '' )? 'file/activity/service/'.$fileID : 'file/activity/service/'.$fileID.'/'.$activityID;
                    redirect($url);
                }
            }
        }

        $tourFileActivityDetailRepo = $this->doctrine->em->getRepository('file\models\TourFileActivityDetail');
        $tourFileActivityDetails = $tourFileActivityDetailRepo->getActivityDetailsByActivityType($activityID);
        $this->load->helper('hotel/hotel');
        $this->templatedata['activity_details'] =  $tourFileActivityDetails;
        $this->templatedata['page_title'] = 'Service Activity';
        $this->templatedata['file'] = $tourFile;
        $this->templatedata['activity'] = $serviceActivity;
        $this->templatedata['maincontent'] = 'file/activities/service_activity';
        $this->load->theme('master', $this->templatedata);
    }

    public function validate_hotel_service_detail($val){
        $details = $this->input->post('detail');
        $hasSpecialRateError = FALSE;
        $hasRateError = FALSE;
        $hasQtyError = FALSE;
        $hasServiceTypeError = FALSE;

        if( count($details) > 0 ){
            foreach($details as $key => $value){
                if( ! isset($value['quantity']) or $value['quantity'] == '' ){ $hasQtyError = TRUE; }
                if( ! isset($value['service']) or $value['service'] == ''){ $hasServiceTypeError = TRUE; }
                if( ! isset($value['actualRate']) or $value['actualRate'] == '' or ! is_numeric($value['actualRate']) ){
                    if( ! isset( $value['complimentary'] ) or $value['complimentary'] == false ){
                        if( ! isset($value['specialRate']) or $value['specialRate'] == '' or !is_numeric($value['specialRate']) ){
                            $hasRateError = TRUE;
                        }
                    }
                }
                if( isset($value['applySpecialRate']) and $value['applySpecialRate'] == TRUE ){
                    if(  $value['specialPaymentAmount'] == "" ){  $hasSpecialRateError = TRUE; }
                }
            }
        }

        $errorMessage = array();

        if( $hasServiceTypeError ){ $errorMessage[] = 'Please specify the service types'; }
        if( $hasQtyError ){ $errorMessage[] = 'Quantity must be defined for every service detail.'; }
        if( $hasRateError ){ $errorMessage[] = 'Detail must be complimentary or Special rate must be applied if Service Rate is not defined.'; }
        if( $hasSpecialRateError ){ $errorMessage[] = 'Be sure that payable amount is defined for special rate, if checked Apply special rate.'; }

        if($hasRateError or $hasQtyError or $hasSpecialRateError or $hasServiceTypeError){
            $this->form_validation->set_message('validate_hotel_detail', implode('<br />', $errorMessage));
            return false;
        }
        else{
            return true;
        }
    }
}