<?php

namespace Yarsha\HotelRates\RoomBasis;


use hotel\models\Rate;
use hotel\models\Hotel;
use hotel\models\RateDetail;
use Yarsha\HotelRates\HotelRateInterface;

class NonSeasonal implements HotelRateInterface{

    private  $template;
    private $hotel;
    private $doctrine;

    public function __construct($hotel){
        $this->template = 'hotel/rates/room_basis_non_seasonal';
        $this->hotel = $hotel;
        $this->doctrine = \CI::$APP->doctrine;
    }

    public function getTemplate(){
        return $this->template;
    }

    public function getRates(){
        $rates = $this->hotel->getRates();
        $response = [];

        if(count($rates)){
            $count = 0;

           foreach($rates as $r){

               if( $r->getStatus() == FALSE or $r->getRateStrategy() != Hotel::HOTEL_RATE_VARIATION_STRATEGY_NONE or $r->getType() == Rate::RATE_TYPE_SERVICE) continue;
               if( $r->getPackage() ) continue;

               $response[$count]['rateID'] = $r->id();
               $response[$count]['market'] = ( $r->getMarket() )? $r->getMarket()->id() : NULL;
               $response[$count]['expDate'] = ( $r->getExpiryDate() )? $r->getExpiryDate()->format('Y-m-d') : '';
               $response[$count]['category'] = ( $r->getRoomCategory() )? $r->getRoomCategory()->id() : '';
               $response[$count]['plan'] = ( $r->getRoomPlan() )? $r->getRoomPlan()->id() : '';
               $response[$count]['type'] = ( $r->getRoomType() )? $r->getRoomType()->id() : '';

               $rateDetails = $r->getRateDetails();
               if( count($rateDetails) ){
                   $rateDetails = $rateDetails[0];
                   $response[$count]['amount'] = $rateDetails->getCharge();
                   $response[$count]['extraBed'] = $rateDetails->getExtraBedCharge();
               }

               $count++;
           }
        }

        return $response;
    }

    public function updateRates($post){

        $hotelRates = $post['rBNS'];
        $hotelRateRepository = $this->doctrine->em->getRepository('hotel\models\Rate');
        $oldRates = $hotelRateRepository->findBy([ 'hotel' => $this->hotel->id(), 'status' => 1 ]);

        if( count($oldRates) ){
            foreach($oldRates as $oldRate){
                if( $oldRate->getRateStrategy() == Hotel::HOTEL_RATE_VARIATION_STRATEGY_NONE and ! $oldRate->getPackage() ){
                    $oldRate->setStatus(FALSE);
                    $this->doctrine->em->persist($oldRate);
                }
            }
        }

        $marketRepo = $this->doctrine->em->getRepository('market\models\Market');
        $categoryRepo = $this->doctrine->em->getRepository('hotel\models\HotelRoomCategory');
        $planRepo = $this->doctrine->em->getRepository('hotel\models\HotelRoomPlan');
        $typeRepo = $this->doctrine->em->getRepository('hotel\models\HotelRoomType');

        foreach($hotelRates as $rate){
            $marketID = $rate['market'];
            $expiryDate = $rate['expiryDate'];
            $categoryID = $rate['category'];
            $typeID = $rate['type'];
            $planID = $rate['plan'];
            $amount = $rate['amount'];
            $extraBedAmount = $rate['extraBed'];

            if( $categoryID == '' or $typeID == '' or $planID == '' ) continue;

            $market = ( $marketID != "")? $marketRepo->find($marketID) : NULL;

            $roomCategory = $categoryRepo->find($categoryID);
            $roomPlan = $planRepo->find($planID);
            $roomType = $typeRepo->find($typeID);

            $newRate = new Rate();
            $newRate->setRateStrategy(Hotel::HOTEL_RATE_VARIATION_STRATEGY_NONE);
            $newRate->setExpiryDate(new \DateTime($expiryDate));
            $newRate->setHotel($this->hotel);
            if( !is_null($market)){
                $newRate->setMarket($market);
            }
            $newRate->setRoomCategory($roomCategory);
            $newRate->setRoomPlan($roomPlan);
            $newRate->setRoomType($roomType);

            $newRateDetails = new RateDetail();
            $newRateDetails->setRate($newRate);
            $newRateDetails->setCharge($amount);
            $newRateDetails->setExtraBedCharge($extraBedAmount);
            $this->doctrine->em->persist($newRateDetails);

            $newRate->addRateDetail($newRateDetails);

            $this->doctrine->em->persist($newRate);
        }

        try{
            $this->doctrine->em->flush();
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }

    }
}