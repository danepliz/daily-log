<?php

namespace Yarsha\HotelRates;


use hotel\models\Rate;
use hotel\models\Hotel;
use hotel\models\RateDetail;
use Yarsha\HotelRates\HotelRateInterface;

class ServiceRate implements HotelRateInterface{

    private  $template;
    private $hotel;
    private $doctrine;

    public function __construct($hotel){
        $this->template = 'hotel/rates/services';
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

               if( $r->getStatus() == FALSE or $r->getType() != Rate::RATE_TYPE_SERVICE  ) continue;

               $response[$count]['rateID'] = $r->id();
               $response[$count]['market'] = ( $r->getMarket() )? $r->getMarket()->id() : NULL;
               $response[$count]['expDate'] = ( $r->getExpiryDate() )? $r->getExpiryDate()->format('Y-m-d') : '';
               $response[$count]['outlet'] = ( $r->getOutlet() )? $r->getOutlet()->id() : '';
               $response[$count]['service'] = ( $r->getService() )? $r->getService()->id() : '';

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

        $hotelRates = $post['serviceRates'];
        $hotelRateRepository = $this->doctrine->em->getRepository('hotel\models\Rate');
        $oldRates = $hotelRateRepository->findBy([ 'hotel' => $this->hotel->id(), 'status' => 1, 'type' => "SERVICE" ] );

        if( count($oldRates) ){
            foreach($oldRates as $oldRate){
                if( $oldRate->getStatus() == TRUE ){
                    $oldRate->setStatus(FALSE);
                    $this->doctrine->em->persist($oldRate);
                }
            }
        }

        $marketRepo = $this->doctrine->em->getRepository('market\models\Market');
        $outletRepo = $this->doctrine->em->getRepository('hotel\models\HotelOutlet');
        $serviceRepo = $this->doctrine->em->getRepository('hotel\models\HotelServices');

        foreach($hotelRates as $rate){
            $marketID = $rate['market'];
            $expiryDate = $rate['expiryDate'];
            $outletID = $rate['outlet'];
            $serviceID = $rate['service'];
            $amount = $rate['amount'];

            if( $serviceID == '') continue;

            $market = ( $marketID != "")? $marketRepo->find($marketID) : NULL;

            $outlet = ( $outletID != "")? $outletRepo->find($outletID) : NULL;
            $service = $serviceRepo->find($serviceID);

            $newRate = new Rate();
            $newRate->setType(Rate::RATE_TYPE_SERVICE);
            $newRate->setExpiryDate(new \DateTime($expiryDate));
            $newRate->setHotel($this->hotel);
            if( !is_null($market)){
                $newRate->setMarket($market);
            }
            if( !is_null($outlet) ){
                $newRate->setOutlet($outlet);
            }
            $newRate->setService($service);

            $newRateDetails = new RateDetail();
            $newRateDetails->setRate($newRate);
            $newRateDetails->setCharge($amount);
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