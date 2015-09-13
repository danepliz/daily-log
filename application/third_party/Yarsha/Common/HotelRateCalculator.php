<?php

namespace Yarsha\Common;


use hotel\models\Hotel;
use hotel\models\HotelPackage;

class HotelRateCalculator{

    private $ci;
    private $doctrine;

    public function __construct(){
        $this->ci = \CI::$APP;
        $this->doctrine = $this->ci->doctrine;
    }

    public function formatMoney( $amount ){
        return number_format($amount, 2, '.', '');
    }

    public function calculateRate($hotel, $params = array()){

        $hotelID = $hotel->id();
        $params['hotel'] = $hotelID;
        $params['rateStrategy'] = $hotel->getRateVariationStrategy();
        $params['applySeasonalRate'] = $hotel->getRateVariationStrategy() == Hotel::HOTEL_RATE_VARIATION_STRATEGY_SEASONAL ? TRUE : FALSE;
        $params['isPackageBasis'] = (isset($params['bookingType']) and $params['bookingType'] == Hotel::HOTEL_BOOKING_TYPE_PACKAGE_BASIS)? TRUE : FALSE;
        $params['isServiceBasis'] = (isset($params['serviceType']) and $params['serviceType'] != '')? TRUE : FALSE;

        if( $params['isServiceBasis'] ){
            $params['applySeasonalRate'] = FALSE;
        }

        $rateRepository = $this->doctrine->em->getRepository('hotel\models\Rate');
        $rate = $rateRepository->getRatesForCalculation($params);

        $data = new \stdClass();

        if($rate){

            $paymentStrategy = $hotel->getPaymentStrategy();
            $paymentStrategyPercent = $hotel->getPaymentStrategyPercent();

            $expiryDate = $rate['expiryDate'];
            $arrivalDate = new \DateTime($params['arrivalDate']);
            $departureDate = new \DateTime($params['departureDate']);

            $isRateExpired = FALSE;
            $isExpiring = FALSE;

            if( ! $params['applySeasonalRate'] ){
//                $isRateExpired = ($expiryDate > new \DateTime()) ? FALSE : TRUE;
                $isRateExpired = ($expiryDate > $arrivalDate) ? FALSE : TRUE;
                if( ! $isRateExpired ){
                    $isExpiring = ( $departureDate > $expiryDate )? TRUE : FALSE;
                }
            }

            $dateDiff = date_diff($departureDate, $arrivalDate);
            $numberOfNights = $dateDiff->d;
            $quantity = $params['quantity'];

            $numberOfPax = $params['pax'];
            $charge = $rate['amount'];

            if( $params['isServiceBasis'] ){
                $totalAmount = $rate['amount'] * $quantity;
                $totalCharge = ( $totalAmount + ( $paymentStrategyPercent / 100 ) * $totalAmount );

            }elseif( $params['isPackageBasis'] ){

                $package = $this->doctrine->em->find('hotel\models\HotelPackage', $params['package']);
                $packageType = $package->getType();
                $packageNumberOfNights = $packageType == HotelPackage::PACKAGE_TYPE_MAIN ? $package->getNumberOfNights() : 1;

                $additionalNights = ( $numberOfNights > $packageNumberOfNights )? $numberOfNights - $packageNumberOfNights : 0;

                if( $packageType == HotelPackage::PACKAGE_TYPE_MAIN ){
                    $packageCharge = ( $numberOfPax == 1 )?  $quantity * ( $charge + $rate['supplement'] ) : $quantity * $charge;
                    $additionalCharge = ( $additionalNights > 0 )? $quantity * $additionalNights * ( $rate['additional'] + $rate['supplement'] ) : 0;
                    $packageMainCharge = $packageCharge + $additionalCharge;
                    $totalCharge = $packageMainCharge + ( ( $paymentStrategyPercent / 100 ) * $packageMainCharge );

                }else{
                    $packageCharge = $charge * $quantity;
//                    $additionalCharge = ( $additionalNights > 0 )? $quantity * $additionalNights * $rate['additional'] : 0;
                    $additionalCharge = 0;
                    $totalCharge = $packageCharge + $additionalCharge;
                }

            }else{
                $totalAmount = $rate['amount'] * $quantity * $numberOfNights;
                $extraBedCharge = $params['extraBed'] * $rate['extraBed'] * $numberOfNights;
                $total = $totalAmount + $extraBedCharge;
                $totalCharge = ( $total + ( $paymentStrategyPercent / 100 ) * $total );
            }

            $calculatedAmount = ( $params['margin'] != '' and $params['margin'] > 0 ) ? ( $totalCharge + ( $params['margin'] / 100 ) * $totalCharge ) : $totalCharge;

            $data->payableAmount = $this->formatMoney($totalCharge);
            $data->billingAmount = $this->formatMoney($calculatedAmount);

            $data->margin = $params['margin'];
            $data->paymentStrategy = $paymentStrategy;
            $data->paymentStrategyPercent = $paymentStrategyPercent;
            $data->numberOfNights = $numberOfNights;
            $data->isExpired = $isRateExpired;
            $data->isExpiring = $isExpiring;
            $data->expiryDate = $expiryDate->format('Y-m-d');
            $data->numberOfRooms = (int)$quantity;
            $data->actualRate = $rate['rateID'];
            $data->actualRateDetail = $rate['rateDetailsID'];
        }else{
            $data = NULL;
        }

        return $data;
    }

}