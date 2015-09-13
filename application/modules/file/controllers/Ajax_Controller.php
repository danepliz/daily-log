<?php

use file\models\TourFileActivityHotel;
use file\models\TourFileActivityDetailHotel;
use Yarsha\Common\HotelRateCalculator;
use file\models\TourFileActivity;
use hotel\models\Hotel;


class Ajax_Controller extends  Xhr
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file/xo');
    }

    public function getActivityForm($fileID, $activityID)
    {
        $activity = $this->doctrine->em->find('file\models\TourFileActivity', $activityID);

        if ($activity) {

            if ($activity instanceof TourFileActivityHotel) {
                return $this->getHotelActivityForm($fileID, $activityID);
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Not a Hotel Activity.';
                echo json_encode($response);
            }

        } else {
            $response['status'] = 'error';
            $response['message'] = 'Activity Not Found.';
            echo json_encode($response);
        }

    }


    public function getHotelActivityForm($fileID, $activityID = "")
    {
        $response = array();
        $data = array();

        $file = $this->doctrine->em->find('file\models\TourFile', $fileID);
        $data['file'] = $file;

        $this->load->helper('hotel/hotel');

        if ($activityID and $activityID !== "") {

            $activity = $this->doctrine->em->find('file\models\TourFileActivityHotel', $activityID);

            if ($activity) {
                $data['activity'] = $activity;
            } else {
                $response['status'] = 'failure';
                $response['message'] = 'Hotel Activity Form Detail not found';
            }
        }

        $this->load->theme('file/xo_forms/hotel', $data);
    }

    public function getHotelRoomOptions($hotelId)
    {
        $hotel = $this->doctrine->em->find('hotel\models\Hotel', $hotelId);

        $categories = $hotel->getRoomCategories();
        $types = $hotel->getRoomTypes();
        $plans = $hotel->getRoomPlans();

        $categoriesOptions = '<option value="">-- CATEGORY --</option>';
        $typesOptions = '<option value="">-- TYPE --</option>';
        $plansOptions = '<option value="">-- PLAN --</option>';

        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $categoriesOptions .= '<option value="' . $category->id() . '">' . $category->getName() . '</option>';
            }
        }

        if (count($types) > 0) {
            foreach ($types as $type) {
                $typesOptions .= '<option value="' . $type->id() . '" data-qty="' . $type->getQuantity() . '">' . $type->getName() . '</option>';
            }
        }

        if (count($plans) > 0) {
            foreach ($plans as $plan) {
                $plansOptions .= '<option value="' . $plan->id() . '">' . $plan->getName() . '</option>';
            }
        }

        $response = [
            'status' => 'success',
            'categories' => $categoriesOptions,
            'types' => $typesOptions,
            'plans' => $plansOptions
        ];

        echo json_encode($response);

    }

    public function getHotelPackages($hotelID)
    {
        $hotel = $this->doctrine->em->find('hotel\models\Hotel', $hotelID);
        $optionMain = '<option value="">-- PACKAGE --</option>';
        $optionExtra = '<option value="">-- EXTRA --</option>';
        if ($hotel) {
            $packages = $hotel->getPackages();
            if (count($packages)) {
                foreach ($packages as $p) {
                    $opt = '<option value="' . $p->id() . '">' . $p->getName() . '</option>';
                    if ($p->getType() == \hotel\models\HotelPackage::PACKAGE_TYPE_MAIN) {
                        $optionMain .= $opt;
                    } else {
                        $optionExtra .= $opt;
                    }
                }
            }
        }
        echo json_encode(['main' => $optionMain, 'extra' => $optionExtra]);
    }

    public function getHotelPayableCurrencies($hotelID)
    {
        $hotel = $this->doctrine->em->find('hotel\models\Hotel', $hotelID);

        $currencies = $hotel->getPayableCurrencies();

        $options = '<option value="">-- CURRENCY --</option>';
        if (count($currencies) > 0) {
            foreach ($currencies as $cur) {
                $options .= '<option value="' . $cur->id() . '">' . $cur->getIso3() . '</option>';
            }
        }

        $response = [
            'status' => 'success',
            'currency' => $options
        ];

        echo json_encode($response);

    }

    public function getHotelBookingTypes($hotelID)
    {
        $hotel = $this->doctrine->em->find('hotel\models\Hotel', $hotelID);

        $optionsArr = [];

        if ($hotel->hasBookingTypeRoomBasis()) {
            $optionsArr[Hotel::HOTEL_BOOKING_TYPE_ROOM_BASIS] = strtoupper(Hotel::$bookingTypes[Hotel::HOTEL_BOOKING_TYPE_ROOM_BASIS]);
        }

        if ($hotel->hasBookingTypePackageBasis()) {
            $optionsArr[Hotel::HOTEL_BOOKING_TYPE_PACKAGE_BASIS] = strtoupper(Hotel::$bookingTypes[Hotel::HOTEL_BOOKING_TYPE_PACKAGE_BASIS]);
        }

        $options = '';
        if (count($optionsArr) > 0) {
            foreach ($optionsArr as $key => $v) {
                $options .= '<option value="' . $key . '">' . $v . '</option>';
            }
        } else {
            $options = '<option value="">-- BOOKING TYPE --</option>';
        }

        $response = [
            'status' => 'success',
            'bookingType' => $options
        ];

        echo json_encode($response);

    }

    public function submitActivityForm()
    {

        $response = array();
        $response['status'] = 'error';
        $response['message'] = 'Unable to complete the request.';

        if ($this->input->post()) {
            $post = $this->input->post();

            if (isset($post['activity_type'])) {
                if ($post['activity_type'] == "HOTEL") {
                    return $this->postHotelActivityForm($post);
                } else {
                    $response['message'] = 'Something went wrong. Please Contact Technical Support.';
                }

            } else {
                $response['message'] = 'Something went wrong. Please Contact Technical Support.';
            }
        }
        echo json_encode($response);
    }

    public function postHotelActivityForm($post)
    {

        $response = array();
        $response['status'] = 'error';

        $response['post'] = $post;
//        echo json_encode($post); die;

        $details = $post['detail'];

        if (count($details) > 0) {
            $hasSpecialRateError = FALSE;

            foreach ($details as $key => $value) {
                $response['test_' . $key] = $value;
                if (isset($value['applySpecialRate']) and $value['applySpecialRate'] == TRUE) {
                    $response['test'] = $value['applySpecialRate'];
                    if ($value['specialRate'] == "") {
                        $hasSpecialRateError = TRUE;
                    }
                }
            }
        }

        $isFormValid = ($hasSpecialRateError) ? FALSE : TRUE;

        if ($isFormValid) {
            $arrivalDate = new DateTime($post['arrival_date']);
            $departureDate = new DateTime($post['departure_date']);
            $hotelID = $post['hotel'];
            $description = $post['description'];
            $currentUser = Current_User::user();
            $tourFileID = $post['file_id'];
            $estimatedTimeOfArrival = $post['estimatedArrivalTime'];
            $estimatedDepartureTime = $post['estimatedDepartureTime'];
            $confirmationNumber = $post['confirmationNumber'];
            $noteOfArrivalAndDeparture = $post['noteOfArrivalAndDeparture'];
            $hotel = $this->doctrine->em->find('hotel\models\Hotel', $hotelID);
            $tourFile = $this->doctrine->em->find('file\models\TourFile', $tourFileID);
            $activityID = $post['activity_id'];

            $dateDiff = date_diff($departureDate, $arrivalDate);
            $numberOfNights = $dateDiff->d;

            $tourActivity = ($activityID and $activityID !== "") ? $this->doctrine->em->find('file\models\TourFileActivity', $activityID) : new TourFileActivityHotel();
            $tourActivity->setCreatedBy($currentUser);
            $tourActivity->setArrivalDate($arrivalDate);
            $tourActivity->setDepartureDate($departureDate);
            $tourActivity->setTourFile($tourFile);
            $tourActivity->setDescription($description);
            $tourActivity->setHotel($hotel);
            $tourActivity->setArrivalDepartureNote($noteOfArrivalAndDeparture);
            $tourActivity->setConfirmationNumber($confirmationNumber);
            $tourActivity->setNumberOfNights($numberOfNights);

            if ($estimatedTimeOfArrival !== '') {
                $arrivalTime = new DateTime($estimatedTimeOfArrival);
                $tourActivity->setArrivalTime($arrivalTime);
            }

            if ($estimatedDepartureTime !== '') {
                $departureTime = new DateTime($estimatedDepartureTime);
                $tourActivity->setDepartureTime($departureTime);
            }

            $this->doctrine->em->persist($tourActivity);

            /* Activity Details Persistence */
            if (count($details) > 0) {
                foreach ($details as $k => $val) {

                    $categoryID = $val['category'];
                    $typeID = $val['type'];
                    $planID = $val['plan'];
                    $numberOfRooms = $val['quantity'];
                    $rateID = $val['actualRate'];
                    $totalAmount = $val['actualAmount'];
                    $specialRate = $val['specialRate'];
                    $specialRateReason = $val['specialRateReason'];
                    $activityDetailID = $val['activityDetailID'];

                    $appliedRate = $this->doctrine->em->find('hotel\models\Rate', $rateID);

                    $isComplimentary = (isset($val['complimentary']) and $val['complimentary'] == TRUE) ? TRUE : FALSE;
                    $isSpecialRateApplied = (isset($val['applySpecialRate']) and $val['applySpecialRate'] == TRUE) ? TRUE : FALSE;

                    $category = $this->doctrine->em->find('hotel\models\HotelRoomCategory', $categoryID);
                    $type = $this->doctrine->em->find('hotel\models\HotelRoomType', $typeID);
                    $plan = $this->doctrine->em->find('hotel\models\HotelRoomPlan', $planID);

                    $tourFileActivityDetail = ($activityDetailID and $activityDetailID !== "") ? $this->doctrine->em->find('file\models\TourFileActivityDetail', $activityDetailID) : new TourFileActivityDetailHotel();

                    if ($appliedRate) $tourFileActivityDetail->setHotelRate($appliedRate);

                    if ($isSpecialRateApplied) {
                        $tourFileActivityDetail->markAsSpecialRateApplied();
                        $tourFileActivityDetail->setSpecialRate($specialRate);
                        $tourFileActivityDetail->setReasonForSpecialRate($specialRateReason);
                    }

                    $tourFileActivityDetail->setRoomCategory($category);
                    $tourFileActivityDetail->setRoomPlan($plan);
                    $tourFileActivityDetail->setRoomType($type);
                    $tourFileActivityDetail->setNumberOfRooms($numberOfRooms);
                    $tourFileActivityDetail->setTourActivity($tourActivity);
                    if ($isComplimentary) {
                        $tourFileActivityDetail->markAsComplimentary();
                    } else {
                        $tourFileActivityDetail->unMarkAsComplimentary();
                    }
                    $tourFileActivityDetail->setTotalAmount($totalAmount);

                    $tourActivity->addDetail($tourFileActivityDetail);
                    $this->doctrine->em->persist($tourFileActivityDetail);

                }
            }

            try {
                $this->doctrine->em->flush();
                $this->message->set('Hotel Activity Added Successfully.', 'success', TRUE, 'feedback');
                $response['status'] = 'success';
            } catch (\Exception $e) {
                $response['message'] = $e->getMessage();
            }


        } else {
            $msg = 'Form Validation Failed. Please review the Activity.';
            if (!$hasSpecialRateError) {
                $msg = 'Be sure that special rate is defined, if special rate is to be applied. ';
            }
            $response['message'] = $msg;
        }

        echo json_encode($response);
    }

    public function calculateHotelRate()
    {
        $response['status'] = 'error';
        $response['message'] = 'Unable to calculate rate.';
        if ($this->input->post()) {
            $post = $this->input->post();
            $hotel = (isset($post['hotel']) and $post['hotel'] != '') ? $this->doctrine->em->find('hotel\models\Hotel', $post['hotel']) : NULL;
            if ($hotel) {
                $params['market'] = (isset($post['market'])) ? $post['market'] : '';
                $params['bookingType'] = (isset($post['bookingType'])) ? $post['bookingType'] : '';
                $params['roomCategory'] = (isset($post['category'])) ? $post['category'] : '';
                $params['roomType'] = (isset($post['type'])) ? $post['type'] : '';
                $params['roomPlan'] = (isset($post['plan'])) ? $post['plan'] : '';
                $params['market'] = (isset($post['market'])) ? $post['market'] : '';
                $params['quantity'] = (isset($post['quantity'])) ? $post['quantity'] : '';
                $params['arrivalDate'] = (isset($post['arrivalDate'])) ? $post['arrivalDate'] : '';
                $params['departureDate'] = (isset($post['departureDate'])) ? $post['departureDate'] : '';
                $params['margin'] = (isset($post['margin'])) ? $post['margin'] : 0;
                $params['extraBed'] = (isset($post['extraBed'])) ? $post['extraBed'] : 0;
                $params['package'] = (isset($post['package'])) ? $post['package'] : '';
                $params['pax'] = (isset($post['pax'])) ? $post['pax'] : 0;

                $params['outlet'] = (isset( $post['outlet'] )) ? $post['outlet'] : '';
                $params['service'] = (isset( $post['service'] )) ? $post['service'] : '';
                $params['serviceType'] = (isset( $post['serviceType'] )) ? $post['serviceType'] : '';


                $calculator = new HotelRateCalculator();
                $result = $calculator->calculateRate($hotel, $params);

                if ($result) {
                    $response['status'] = 'success';
                    $response['message'] = $result;
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Rates not found for the given criteria. Please contact administrative officer.';
                }
            } else {
                $response['message'] = 'Hotel must be defined';
            }
        }
        echo json_encode($response);
    }

    public function mergedEmail()
    {

        if ($this->input->post()) {
            $post = $this->input->post();
//            $activityID = $post['activityID'];
//            if( $activityID == '' ) redirect('dashboard');
//            $activity = $this->doctrine->em->find('file\models\TourFileActivity', $activityID);
//            if( ! $activity ) redirect('dashboard');

            $emails = $this->input->post('emails');
            $emailCount = 0;
            $clientsEmail = array();

            if (isset($emails['agent']) and count($emails['agent']) > 0) {
                $emailCount++;
            }
            if (isset($emails['hotel']) and count($emails['hotel']) > 0) {
                $emailCount++;
            }
            if (isset($emails['account']) and count($emails['account']) > 0) {
                $emailCount++;
            }
            if (isset($emails['client']) and count($emails['client']) > 0) {
                foreach ($emails['client'] as $cem) {
                    if ($cem !== "") {
                        $clientsEmail[] = $cem;
                        $emailCount++;
                    }
                }
            }

            $emails['client'] = $clientsEmail;
            $response['status'] = 'success';
            $response['message'] = 'Email sent successfully';

            if ($emailCount == 0) {
                $response['status'] = 'error';
                $response['message'] = 'Please select atleast one email before sending.';
            } else {
                $mergedXos = $post['merged_xos'];
                $xoRepo = $this->doctrine->em->getRepository('file\models\TourFileActivity');
                $fileNumber = '000';
                $receivers = [];
                $activityType = '';
                $data = [];

                foreach ($mergedXos as $xo) {
                    $xoObj = $xoRepo->findOneBy(array('xoNumber' => $xo));
                    $activityData = $this->yarsha->am->getActivityDetail($xoObj);
                    $data[] = $activityData;
                    $fileNumber = $activityData['fileNumber'];
                    $receivers = $activityData['emailReceivers'];
                    $activityType = $activityData['activityType'];
                }

                $xoPdf = './assets/uploads/to/' . $fileNumber . '_merged.pdf';

                $tourFile = $this->doctrine->em->find('file\models\TourFile', $fileNumber);

                $logoLink = base_url() . 'assets/themes/yarsha/resources/images/brand.png';
                $stampLink = Options::get('config_stamp', '');
                $stampLocation = './' . substr(Options::get('config_stamp', ''), strlen(base_url()));
                $templateData['logoSrc'] = $logoLink;
                $templateData['stampSrc'] = $stampLink;
                $templateData['data'] = $data;
                $templateData['tourFile'] = $tourFile;
                $templateData['merged_xos'] = $mergedXos;
                $templateData['tourFileNumber'] = $fileNumber;

                $emailDetails = array();

                foreach ($receivers as $rc) {

                    if (isset($emails[$rc]) and count($emails[$rc]) > 0) {
                        $templateData['emailFor'] = $rc;
                        $emailBody = $this->load->theme('file/template/' . $activityType . '_mergedPreview', $templateData, TRUE);
//                    \Doctrine\Common\Util\Debug::dump($emailBody);die;

                        $templateData['logoSrc'] = './assets/themes/yarsha/resources/images/brand.png';
                        $templateData['stampSrc'] = $stampLocation;
                        $templateData['logoSrc'] = $logoLink;
                        $templateData['stampSrc'] = $stampLink;

                        try {
                            $this->load->library('dompdf_gen');
                            $dompdf = new DOMPDF();
                            $dompdf->load_html($this->load->theme('file/template/' . $activityType . '_mergedPreview', $templateData, TRUE));
                            $dompdf->render();
                            $output = $dompdf->output();
                            file_put_contents($xoPdf, $output);
                        } catch (\Exception $e) {
                            log_message('error', $e->getMessage());
                        }

                        $emailDetails[] = array(
                            'for' => $rc,
                            'to' => implode(',', $emails[$rc]),
                            'subject' => ucfirst($rc) . ' Exchange Order',
                            'message' => $emailBody,
                        );
                    }
                }

                if (isset($emails['client']) and count($emails['client']) > 0) {
                    $clientsEmail = array();
                    foreach ($emails['client'] as $cem) {
                        if ($cem !== "") {
                            $clientsEmail[] = $cem;
                        }
                    }
                }

                $emailError = FALSE;
                $errorMessage = array();
                if (count($emailDetails) > 0) {

                    $this->load->library('email');

                    foreach ($emailDetails as $detail) {
                        $this->email->to($detail['to']);
                        $this->email->from('noreply@yetibilling.com', "Yetibilling");
                        $this->email->subject($detail['subject']);
                        $this->email->message($detail['message']);
                        $this->email->attach($xoPdf);

                        if (!$this->email->send()) {
                            log_message('error', $this->email->print_debugger());
                            $emailError = TRUE;
                            $errorMessage[] = $detail['for'];
                        }
                    }
                }

                if ($emailError) {
                    $response['status'] = 'error';
                    $response['message'] = 'Unable to send emails for ' . implode(', ', $errorMessage);
                    if (file_exists($xoPdf)) {
                        unlink(FCPATH . $xoPdf);
                    }
                } else {
                    $response['status'] = 'success';
                    $response['message'] = 'Emails send successfully.';
                    unlink(FCPATH . $xoPdf);
                }
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Unable to process your request.';
        }
        echo json_encode($response);
    }


    public function checkXO($activityID)
    {
        $response['status'] = 'error';
        $response['message'] = '';

        if ($activityID == '') {
            $response['message'] = 'No activity selected';
        }

        $data = [];

        $activity = $this->doctrine->em->getRepository('file\models\TourFile');
        $activityDetails = $activity->getActivitiesByTourFile($activityID);
        $errorCount = 0;
        foreach ($activityDetails as $ad) {
            $margin = $ad->getMargin();
            if ($margin == '' or $margin == null) {
                $errorCount++;
            }
            $data['margin'][] = $margin;
            $data['error'][] = $errorCount;
        }

        $response['data'] = $data;

        $response['errorCount'] = $errorCount;

        if ($errorCount == 0) {
            $response['status'] = 'success';
        }

        echo json_encode($response);
    }
//        if( $activityStatus == TourFileActivity::ACTIVITY_STATUS_VOID );
    public function voidXo($id='')
    {
        $hotel_activity = ($id == '') ? NULL : $this->doctrine->em->find('file\models\TourFileActivity', $id);
        $response['status'] = 'error';
        $response['message'] = '';

        if (!is_null($hotel_activity)) {
            $hotel_activity->markAsVoid();
            $this->doctrine->em->persist($hotel_activity);
            try {
                $this->doctrine->em->flush();
                log_message('info', 'HotelActivityDetail marked as void');
                $response['status'] = 'success';
                $response['message'] = 'The HotelActivityDetail has been changed as Void.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to  make void HotelActivityDetail. ' . $e->getMessage();
            }
         }
        else {
            $response['message'] = 'Hotel Activity Detail not found.';
        }
        echo json_encode($response);
    }

    public function deleteXo($id = '')
    {

        $hotel_activity = ($id == '') ? NULL : $this->doctrine->em->find('file\models\TourFileActivity', $id);
        $response['status'] = 'error';
        $response['message'] = '';

//        \Doctrine\Common\Util\Debug::dump($hotel_activity); die;

        if (!is_null($hotel_activity)) {
            $hotel_activity->markAsDeleted();
            $this->doctrine->em->persist($hotel_activity);
            try {
                $this->doctrine->em->flush();
                log_message('info', 'HotelActivityDetail marked as deleted');
                $response['status'] = 'success';
                $response['message'] = 'The HotelActivityDetail has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to delete HotelActivityDetail. ' . $e->getMessage();
            }
        } else {
            $response['message'] = 'HotelActivityDetail Not Found.';
        }

        echo json_encode($response);
    }




//    public function toggleActivityStatus($TourFileActivityID){
//
//        $response = array();
//
//        if($TourFileActivityID and $TourFileActivityID !== ""){
//            $file = $this->doctrine->em->find('file\models\TourFileActivity', $TourFileActivityID);
//
//            if( $file ){
//
//                $statusDesc = ' Enabled ';
//
//                if( $file->isActive() ){
//                    $file->markAsVoid();
//                    $statusDesc = ' Disabled ';
//                }else{
//                    $file->markAsActive();
//                }
//
//                $this->doctrine->em->persist($file);
//
//                try{
//                    $this->doctrine->em->flush();
//
//                    $response['status'] = 'success';
//                    $response['currentStatus'] = $file->isActive();
//                    $response['message'] = $file->getName().$statusDesc.'successfully.';
//
//                }catch (\Exception $e){
//                    $response['status'] = 'error';
//                    $response['message'] = 'Unable to change status. '.$e->getMessage();
//                }
//            }else{
//                $response['status'] = 'error';
//                $response['message'] = 'Could not find the activity';
//            }
//        }else{
//            $response['status'] = 'error';
//            $response['message'] = 'Could not find the activity';
//        }
//        echo json_encode($response);
//    }


    public function getIssuedToList($activityType)
    {

        switch ($activityType) {
            case TourFileActivity::FILE_ACTIVITY_TYPE_HOTEL :
                $model = 'hotel\models\Hotel';
                break;
            default :
                $model = '';
                break;
        }

        $opt = '<option value="">-- ISSUED TO --</option>';

        if ($model != '') {
            $repo = $this->doctrine->em->getRepository($model);
            $list = $repo->findBy(['status' => 'ACTIVE'], ['name' => 'ASC']);

            if (count($list)) {
                foreach ($list as $l) {
                    $opt .= '<option value="' . $l->id() . '">' . $l->getName() . '</option>';
                }
            }
        }
        $response['status'] = 'success';
        $response['options'] = $opt;

        echo json_encode($response);


    }
}

