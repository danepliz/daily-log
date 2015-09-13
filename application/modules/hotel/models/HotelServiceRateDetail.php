<?php

namespace hotel\models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class RateDetail
 * @ORM\Entity(repositoryClass="HotelRateRepository")
 * @ORM\Table(name="ys_hotel_service_rate_details")
 */
class HotelServiceRateDetail{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="currency\models\Currency")
     */
    private $currency;

    /**
     * @ORM\Column(type="decimal", scale=3, precision=10)
     */
    private $payableRate;

    /**
     * @ORM\ManyToOne(targetEntity="hotel\models\HotelServiceRate", inversedBy="rateDetails")
     */
    private $rate;

    public function id(){
        return $this->id;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getPayableRate()
    {
        return $this->payableRate;
    }

    public function setPayableRate($payableRate)
    {
        $this->payableRate = $payableRate;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;
    }

}

