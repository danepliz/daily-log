<?php

namespace Yarsha\HotelRates\PackageBasis;


use hotel\models\HotelPackage;
use hotel\models\Rate;
use hotel\models\Hotel;
use hotel\models\RateDetail;
use Yarsha\HotelRates\HotelRateInterface;

class Seasonal implements HotelRateInterface{

    private  $template;
    private $hotel;
    private $doctrine;

    public function __construct($hotel){
        $this->template = 'hotel/rates/package_basis_seasonal';
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

                if( $r->getStatus() == FALSE or $r->getType() == Rate::RATE_TYPE_SERVICE or ! $r->getPackage() ) continue;
                $package = $r->getPackage();

                $arr = [];
                $arr['id'] = $r->id();
                $arr['market'] = ( $r->getMarket() )? $r->getMarket()->id() : NULL;
                $arr['expiryDate'] = ( $r->getExpiryDate() )? $r->getExpiryDate()->format('Y-m-d') : '';
                $arr['package'] = ( $package )? $package->id() : '';
                $arr['packageType'] = ( $r->getPackage() )? $r->getPackage()->getType() : '';
                $arr['seasons'] = [];

                $rateDetails = $r->getRateDetails();
                if( count($rateDetails) ){
                    foreach($rateDetails as $rd){
                        if( $rd->getSeason() ){
                            $seasonID = $rd->getSeason()->id();
                            $arr['seasons'][$seasonID] = [
                                'amount' => $rd->getCharge(),
                                'extraBed'=> $rd->getExtraBedCharge(),
                                'additional' => $rd->getAdditionalChargePerNight(),
                                'supplement' => $rd->getSingleSupplementPerNight()
                            ];
                        }
                    }
                }

                if( $package and $package->getType() == HotelPackage::PACKAGE_TYPE_EXTRA ){
                    $extraPackageRates[] = $arr;
                }else{
                    $mainPackageRates[] = $arr;
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

        $postType = 'pBSM';
        $packageType = HotelPackage::PACKAGE_TYPE_MAIN;

        if( isset($post['pBSE']) ){
            $postType = 'pBSE';
            $packageType = HotelPackage::PACKAGE_TYPE_EXTRA;
        }

        $hotelRates = $post[$postType];

        $hotelRateRepository = $this->doctrine->em->getRepository('hotel\models\Rate');
        $oldRates = $hotelRateRepository->findBy([ 'hotel' => $this->hotel->id(), 'status' => 1 ]);

        if( count($oldRates) ){
            foreach($oldRates as $oldRate){
                if( $oldRate->getPackage() and $oldRate->getPackage()->getType() == $packageType and $oldRate->getRateStrategy() == Hotel::HOTEL_RATE_VARIATION_STRATEGY_SEASONAL ){
                    $oldRate->setStatus(FALSE);
                    $this->doctrine->em->persist($oldRate);
                }
            }
        }

        $marketRepo = $this->doctrine->em->getRepository('market\models\Market');
        $packageRepo = $this->doctrine->em->getRepository('hotel\models\HotelPackage');
        $seasonRepo = $this->doctrine->em->getRepository('hotel\models\HotelSeason');

        foreach($hotelRates as $rate){
            $marketID = $rate['market'];
            $expiryDate = $rate['expiryDate'];
            $packageID = $rate['package'];

            $seasons = $rate['seasons'];

            if( $packageID == '' ) continue;

            $market = ( $marketID != "")? $marketRepo->find($marketID) : NULL;

            $package = $packageRepo->find($packageID);

            $newRate = new Rate();
            $newRate->setRateStrategy(Hotel::HOTEL_RATE_VARIATION_STRATEGY_SEASONAL);
            $newRate->setExpiryDate(new \DateTime($expiryDate));
            $newRate->setHotel($this->hotel);
            if( !is_null($market)){
                $newRate->setMarket($market);
            }
            $newRate->setPackage($package);

            foreach($seasons as $k => $s){

                $season = $seasonRepo->find($k);

                $newRateDetails = new RateDetail();
                $newRateDetails->setSeason($season);
                $newRateDetails->setRate($newRate);
                $newRateDetails->setCharge($s['amount']);
                $newRateDetails->setAdditionalChargePerNight($s['additional']);
                if( $postType == 'pBSM' ){
                    $newRateDetails->setSingleSupplementPerNight($s['supplement']);
                }
                $this->doctrine->em->persist($newRateDetails);

                $newRate->addRateDetail($newRateDetails);
            }

            $this->doctrine->em->persist($newRate);
        }

        try{
            $this->doctrine->em->flush();
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }



    }
}