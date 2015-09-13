<?php

namespace Yarsha\HotelRates\PackageBasis;


use hotel\models\Hotel;
use hotel\models\HotelPackage;
use Yarsha\HotelRates\HotelRateInterface;
use hotel\models\Rate;
use hotel\models\RateDetail;

class NonSeasonal implements HotelRateInterface{

    private  $template;
    private $hotel;
    private $doctrine;

    public function __construct($hotel){
        $this->template = 'hotel/rates/package_basis_non_seasonal';
        $this->hotel = $hotel;
        $this->doctrine = \CI::$APP->doctrine;
    }

    public function getTemplate(){
        return $this->template;
    }

    public function getRates(){

        $rates = $this->hotel->getRates();
        $mainPackageRates = [];
        $extraPackageRates = [];

        if(count($rates)){
            $count = 0;

            foreach($rates as $r){
                $data = [];
                if( $r->getStatus() == FALSE or $r->getRateStrategy() != Hotel::HOTEL_RATE_VARIATION_STRATEGY_NONE) continue;
                if( ! $r->getPackage() ) continue;

                $package = $r->getPackage();

                $data['rateID'] = $r->id();
                $data['market'] = ( $r->getMarket() )? $r->getMarket()->id() : NULL;
                $data['expDate'] = ( $r->getExpiryDate() )? $r->getExpiryDate()->format('Y-m-d') : '';
                $data['package'] = ( $package )? $package->id() : '';

                $rateDetails = $r->getRateDetails();
                if( count($rateDetails) ){
                    $rateDetails = $rateDetails[0];
                    $data['amount'] = $rateDetails->getCharge();
                    $data['additional'] = $rateDetails->getAdditionalChargePerNight();
                    $data['supplement'] = $rateDetails->getSingleSupplementPerNight();
                }

                if( $package and $package->getType() == HotelPackage::PACKAGE_TYPE_EXTRA ){
                    $extraPackageRates[] = $data;
                }else{
                    $mainPackageRates[] = $data;
                }

                $count++;
            }
        }

        return [
            'main' => $mainPackageRates,
            'extra' => $extraPackageRates
        ];
    }

    public function updateRates($post){

        $hotelRates = isset($post['pNSM'])? $post['pNSM'] : [];
        $extraRates = isset($post['pNSE'])? $post['pNSE'] : [];
        $newArray = array_merge($hotelRates, $extraRates);

        $packageType = isset($post['pNSM'])? HotelPackage::PACKAGE_TYPE_MAIN : HotelPackage::PACKAGE_TYPE_EXTRA;


        $hotelRateRepository = $this->doctrine->em->getRepository('hotel\models\Rate');
        $oldRates = $hotelRateRepository->findBy([ 'hotel' => $this->hotel->id(), 'status' => 1 ]);

        if( count($oldRates) ){
            foreach($oldRates as $oldRate){
                if( $oldRate->getRateStrategy() == Hotel::HOTEL_RATE_VARIATION_STRATEGY_NONE and $oldRate->getPackage() and $oldRate->getPackage()->getType() ==  $packageType){
                    $oldRate->setStatus(FALSE);
                    $this->doctrine->em->persist($oldRate);
                }
            }
        }

        $marketRepo = $this->doctrine->em->getRepository('market\models\Market');
        $packageRepo = $this->doctrine->em->getRepository('hotel\models\HotelPackage');

        foreach($newArray as $rate){
            $marketID = $rate['market'];
            $expiryDate = $rate['expiryDate'];
            $packageID = $rate['package'];
            $amount = $rate['amount'];
            $additionalAmount = $rate['additional'];
            $supplementAmount = isset($rate['supplement'])? $rate['supplement'] : '';

            if( $packageID == '' ) continue;

            $market = ( $marketID != "")? $marketRepo->find($marketID) : NULL;

            $package = $packageRepo->find($packageID);

            $newRate = new Rate();
            $newRate->setRateStrategy(Hotel::HOTEL_RATE_VARIATION_STRATEGY_NONE);
            $newRate->setExpiryDate(new \DateTime($expiryDate));
            $newRate->setHotel($this->hotel);
            if( !is_null($market)){
                $newRate->setMarket($market);
            }
            $newRate->setPackage($package);

            $newRateDetails = new RateDetail();
            $newRateDetails->setRate($newRate);
            $newRateDetails->setCharge($amount);
            $newRateDetails->setAdditionalChargePerNight($additionalAmount);
            if( $supplementAmount !='' ){
                $newRateDetails->setSingleSupplementPerNight($supplementAmount);
            }
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