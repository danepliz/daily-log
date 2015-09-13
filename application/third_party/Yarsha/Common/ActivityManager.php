<?php
namespace Yarsha\Common;


use file\models\TourFileActivityHotel;
use hotel\models\Hotel;
use Yarsha\Exception\YarshaException;

class ActivityManager{

    private $ci;

    public function __construct(){
        $this->ci = &\CI::$APP;
    }

    public function getActivityDetail($activity, $print = FALSE){

        if($activity ){

            if( ! $print and ! $activity->isXoGenerated() ) throw new YarshaException(YarshaException::ACTIVITY_X0_ALREADY_GENERATED);


            if( $activity instanceof TourFileActivityHotel ){
                $activityData = $this->getActivityForHotel($activity);
                $activityData['activityType'] = 'hotel';
                $activityData['emailReceivers'] = array('agent', 'hotel', 'client', 'account');
                $activityData['mainContent']= 'file/activities/hotel_detail';
                $activityData['title'] = ( $activity->getBookingType() == Hotel::HOTEL_BOOKING_TYPE_SERVICE_BASIS )? 'Service Activity' : 'Hotel Activity';
            }else{
                throw new YarshaException(YarshaException::ACTIVITY_IS_NOT_VALID);
            }

            $activityID = $activity->id();

            $tourFile = $activity->getTourFile();
            $agentName = 'N/A';
            $agentContactPersonsEmails = array();

            $agent = $tourFile->getAgent();

            if( $agent ){

                $agentName = $agent->getName();
                $agentContactPerson = $tourFile->getAgentContactPerson();
                if( $agentContactPerson and count($agentContactPerson->getEmails()) > 0 ){
                    foreach($agentContactPerson->getEmails() as $ap){
                        $agentContactPersonsEmails[] = $ap;
                    }
                }
            }

            $accountEmail =  \Options::get('config_accountemail');

            $activityData['emails']['agent'] = array_unique($agentContactPersonsEmails);
            $activityData['emails']['account'] = ( $accountEmail == "" )? array() : array($accountEmail);

            $nationality = $tourFile->getNationality() ? $tourFile->getNationality()->getName() : 'N/A';


            $activityData['activityID'] = $activityID;
            $activityData['status'] = $activity->getStatus();
            $activityData['agent'] = strtoupper($agentName);
            $activityData['client'] = $tourFile->getClient();
            $activityData['nationality'] = strtoupper($nationality);
            $activityData['fileNumber'] = $tourFile->getFileNumber();
            $activityData['pax'] = $tourFile->getNumberOfPax();
            $activityData['child'] = $tourFile->getNumberOfChildren();
            $activityData['infants'] = $tourFile->getNumberOfInfants();
            $activityData['market'] = ($tourFile->getMarket())? strtoupper($tourFile->getMarket()->getName()) : 'ANY';
            $activityData['contactPerson'] = ( $tourFile->getAgentContactPerson() )? strtoupper($tourFile->getAgentContactPerson()->getName()) : 'N/A';
            $activityData['tourOfficer'] = ( $tourFile->getTourOfficer() )? strtoupper($tourFile->getTourOfficer()->getFullName()) : 'N/A';
            $activityData['currentUser'] = \Current_User::user()->getFullName();
            $activityData['xoGeneratedBy'] = ( $activity->getXoGeneratedBy() )? $activity->getXoGeneratedBy()->getFullName() : \Current_User::user()->getFullName();
            $activityData['currency'] = ($activity->getCurrency())? $activity->getCurrency()->getIso3() : 'N/A';
            $activityData['confirmationNumber'] = $activity->getConfirmationNumber();

            $arrivalDate = ($activity->getArrivalDate())? $activity->getArrivalDate()->format('Y-m-d') : '';
            $arrivalTime = ($activity->getArrivalTime())? $activity->getArrivalTime()->format('H:i') : '';
            $deptDate = ($activity->getDepartureDate())? $activity->getDepartureDate()->format('Y-m-d') : '';
            $deptTime = ($activity->getDepartureTime())? $activity->getDepartureTime()->format('H:i') : '';

            $activityData['arrivalDate'] = $arrivalDate.' '.$arrivalTime;
            $activityData['departureDate'] = $deptDate.' '.$deptTime;

            $arrivalMode = $activity->getArrivalMode();
            $departureMode = $activity->getDepartureMode();

            if( $activity->getArrivalNote() ){
                $arrivalMode .= '( '.$activity->getArrivalNote().' )';
            }

            if( $activity->getDepartureNote() ){
                $departureMode .= '( '.$activity->getDepartureNote().' )';
            }

            $activityData['arrivalDesc'] = $arrivalMode;
            $activityData['departureDesc'] = $departureMode;

            $activityData['arrivalNote'] = $activity->getArrivalNote()? : 'N/A';
            $activityData['departureNote'] = $activity->getDepartureNote()? : 'N/A';
            $activityData['arrivalDepartureNote'] = $activity->getArrivalDepartureNote() ? $activity->getArrivalDepartureNote() : 'N/A';
            $activityData['description'] = $activity->getDescription() ? $activity->getDescription() : 'N/A';

            $activityData['xoNumber'] = $activity->getXoNumber();
            $activityData['revertedTimes'] = $activity->getRevertedTimes();
            $activityData['xoDate'] = ($activity->getXoCreatedDate()) ? $activity->getXoCreatedDate()->format('Y-m-d') : '';
//            $activityData['mainContent'] = 'file/hotel/print/hotel_xo';

            if( $print == 'print' ){
//                $activityData['xoNumber'] = $activity->getXoNumber();
//                $activityData['xoDate'] = ($activity->getXoCreatedDate()) ? $activity->getXoCreatedDate()->format('Y-m-d') : '';
                $activityData['mainContent'] = 'file/print/hotel_xo';
            }

            return $activityData;

        }else{
            throw new YarshaException(YarshaException::ACTIVITY_NOT_FOUND_EXCEPTION);
        }
    }


    public function getActivityForHotel($activity){
        $mainContent = '';
        $activityData = array();
        $activityDescription = array();

        $hotelName = 'N/A';
        $hotelContactPersonsEmails = array();

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

        $activityData['emails']['hotel'] = array_unique($hotelContactPersonsEmails);
        $activityData['hotel'] = strtoupper($hotelName);
        $activityData['nights'] = $activity->getNumberOfNights();
        $activityData['bookingType'] = $activity->getBookingType();
        $activityData['tourFileID'] = $activity->getTourFile() ? $activity->getTourFile()->id() : 0;
        $activityString = '';

        $activityDetails = $activity->getDetails();
        $arrivalDate = ( $activity->getArrivalDate() )? $activity->getArrivalDate()->format('Y-m-d') : '';
        $departureDate = ( $activity->getDepartureDate() )? $activity->getDepartureDate()->format('Y-m-d') : '';

        if( count($activityDetails) > 0 ){
            $count = 0;
            foreach($activityDetails as $ad){

                if( $ad->isDeleted() ){ continue; }

                $totalRooms = $ad->getNumberOfRooms();

                $bookingType = $ad->getTourActivity()->getBookingType();

                $activityDescription[$count]['bookingType'] = $bookingType;
                $mainActivityDesc = [];
                $childActivityDesc = [];

                $payableRate = $billingRate = '00.00';
                $specialRateApplied = FALSE;
                $remarks = '';

                $rateId = $ad->getHotelRate() ? $ad->getHotelRate()->id() : '';
                $rateDetailID =  $ad->getHotelRateDetail() ? $ad->getHotelRateDetail()->id() : '';

                $payableRate = $ad->getPayableAmount();
                $billingRate = $ad->getBillingAmount();
                $margin = $ad->getMargin();
                $totalAmount = $ad->getTotalAmount();

                if( $ad->isSpecialRateApplied() and $ad->getSpecialRate() !== "" ){
                    $totalAmount = $ad->getSpecialRate();
                    $specialRateApplied = TRUE;
                    $remarks = '[Special Rate Applied]. '.$ad->getReasonForSpecialRate();
                }
                if( $ad->isComplimentary() ){ $remarks .= ' [Complimentary]'; }

                if( $bookingType == Hotel::HOTEL_BOOKING_TYPE_SERVICE_BASIS){
                    $outlet = ( $ad->getOutlet() )? $ad->getOutlet()->getName() : '';
                    $service = ( $ad->getService() )? $ad->getService()->getName() : '';

                    $activityDescString = $totalRooms.' '.strtoupper($service);
                    if( $outlet!= '' ){
                        $activityDescString .= ' AT '.strtoupper($outlet);
                    }else{
                        $activityDescString .= ' AT ANY OUTLET';
                    }
                    $res = [$activityDescString];

                }elseif( $bookingType == Hotel::HOTEL_BOOKING_TYPE_PACKAGE_BASIS ){
                    $mainPackage = $ad->getPackage()->getName();
                    $extraPackages = $ad->getChildren();

                    if( count($extraPackages) ){
                        $eCount = 0;
                        foreach($extraPackages as $ep){

                            $eRateId = $ep->getHotelRate() ? $ep->getHotelRate()->id() : '';
                            $eRateDetailID =  $ep->getHotelRateDetail() ? $ep->getHotelRateDetail()->id() : '';

                            $extraPckDesc = [$ep->getNumberOfRooms().' '. $ep->getPackage()->getName()];

                            $ePayableRate = $ep->getPayableAmount();
                            $eBillingRate = $ep->getBillingAmount();
                            $eMargin = $ep->getMargin();
                            $eTotalAmount = $ep->getTotalAmount();

                            $childActivityDesc[$eCount]['description'] = implode(' ', $extraPckDesc);
                            $childActivityDesc[$eCount]['payableRate'] = number_format($ePayableRate, 2, '.', '');
                            $childActivityDesc[$eCount]['margin'] = number_format($eMargin, 2, '.', '');
                            $childActivityDesc[$eCount]['billingRate'] = number_format($eBillingRate, 2, '.', '');
                            $childActivityDesc[$eCount]['remarks'] = strtoupper($remarks);
                            $childActivityDesc[$eCount]['specialRateApplied'] = $specialRateApplied;
                            $childActivityDesc[$eCount]['rateID'] = $eRateId;
                            $childActivityDesc[$eCount]['rateDetailID'] = $eRateDetailID;
                            $eCount++;
                        }
                    }

                    $res = [$mainPackage];
                }else{
                    $roomDesc = ($totalRooms == 1) ? ' ROOM' : ' ROOMS';
                    $roomTypeString = $ad->getRoomTypeString();

                    if( $roomTypeString == '' ){
                        $roomTypeString = ($ad->getRoomType())? $ad->getRoomType()->getName() : '';
                    }

                    $roomCategory = ($ad->getRoomCategory())? strtoupper($ad->getRoomCategory()->getName()) : '';
//                    $roomType = ($ad->getRoomType())? strtoupper($ad->getRoomType()->getName()).$roomDesc : $roomDesc;
                    $roomType = ($roomTypeString != '' )? strtoupper($roomTypeString).$roomDesc : $roomDesc;
                    $roomPlan = ($ad->getRoomPlan()) ? $ad->getRoomPlan()->getName() : '';
                    $res = array(
                        str_pad($totalRooms, 2, '0', STR_PAD_LEFT),
                        $roomCategory,
                        $roomType,
                        ' ON '.$roomPlan.' BASIS'
                    );

                    if( $ad->getExtraBed() > 0 ){
                        $res[] = ' + '.$ad->getExtraBed().' Extra Bed ';
                    }
                }

                $note = $ad->getNote();
                $dWithNote = $description = strtoupper(implode(' ',$res));
                if( $note ){
                    $dWithNote = $description . '( '.$note.' )';
                }

                $mainActivityDesc['desc']['account'] = $dWithNote;
                $mainActivityDesc['desc']['others'] = ( $ad->isSpecialRateApplied() )? $description : $dWithNote;
                $mainActivityDesc['description'] = $description;
                $mainActivityDesc['payableRate'] = number_format($payableRate, 2, '.', '');
                $mainActivityDesc['margin'] = number_format($margin, 2, '.', '');
                $mainActivityDesc['billingRate'] = number_format($billingRate, 2, '.', '');
                $mainActivityDesc['remarks'] = strtoupper($remarks);
                $mainActivityDesc['specialRateApplied'] = $specialRateApplied;
                $mainActivityDesc['rateID'] = $rateId;
                $mainActivityDesc['rateDetailID'] = $rateDetailID;

                $activityDescription[$count]['main'] = $mainActivityDesc;
                $activityDescription[$count]['child'] = $childActivityDesc;
                $activityDescription[$count]['isSpecialRateApplied'] = $ad->isSpecialRateApplied();


//                $activityDescription[$count]['desc']['account'] = $dWithNote;
//                $activityDescription[$count]['desc']['others'] = ( $ad->isSpecialRateApplied() )? $description : $dWithNote;
//
//                $activityDescription[$count]['description'] = $description;
//                $activityDescription[$count]['payableRate'] = number_format($payableRate, 2, '.', '');
//                $activityDescription[$count]['margin'] = number_format($margin, 2, '.', '');
//                $activityDescription[$count]['billingRate'] = number_format($billingRate, 2, '.', '');
//                $activityDescription[$count]['remarks'] = strtoupper($remarks);
//                $activityDescription[$count]['specialRateApplied'] = $specialRateApplied;
//                $activityDescription[$count]['rateID'] = $rateId;

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

}