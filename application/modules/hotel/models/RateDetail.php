<?php

namespace hotel\models;

use Doctrine\ORM\Mapping as ORM;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class RateDetail
 * @ORM\Entity(repositoryClass="HotelRateRepository")
 * @ORM\Table(name="ys_hotel_rate_details")
 */
class RateDetail{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Rate", inversedBy="rateDetails")
     */
    private $rate;

    /**
     * @ORM\ManyToOne(targetEntity="HotelSeason")
     */
    private $season;

    /**
     * @ORM\Column(type="decimal", scale=3, precision=10, nullable=true)
     */
    private $charge;

    /**
     * @ORM\Column(type="decimal", scale=3, precision=10, nullable=true)
     */
    private $extraBedCharge;

    /**
     * @ORM\Column(type="decimal", scale=3, precision=10, nullable=true)
     */
    private $additionalChargePerNight;

    /**
     * @ORM\Column(type="decimal", scale=3, precision=10, nullable=true)
     */
    private $singleSupplementPerNight;


    public function id(){
        return $this->id;
    }

    public function getRate(){
        return $this->rate;
    }

    public function setRate($rate){
        $this->rate = $rate;
    }

    public function getSeason()
    {
        return $this->season;
    }

    public function setSeason($season)
    {
        $this->season = $season;
    }

    public function getCharge()
    {
        return $this->charge;
    }

    public function setCharge($charge)
    {
        $this->charge = $charge;
    }

    public function getExtraBedCharge()
    {
        return $this->extraBedCharge;
    }

    public function setExtraBedCharge($extraBedCharge)
    {
        $this->extraBedCharge = $extraBedCharge;
    }

    public function getAdditionalChargePerNight()
    {
        return $this->additionalChargePerNight;
    }

    public function setAdditionalChargePerNight($additionalChargePerNight)
    {
        $this->additionalChargePerNight = $additionalChargePerNight;
    }

    public function getSingleSupplementPerNight()
    {
        return $this->singleSupplementPerNight;
    }

    public function setSingleSupplementPerNight($singleSupplementPerNight)
    {
        $this->singleSupplementPerNight = $singleSupplementPerNight;
    }



}
