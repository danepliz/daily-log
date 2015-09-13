<?php

namespace hotel\models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Hotel
 * @ORM\Entity(repositoryClass="HotelRateRepository")
 * @ORM\Table(name="ys_hotel_service_rates")
 */
class HotelServiceRate{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\ManyToOne(targetEntity="Hotel")
     */
    private $hotel;


    /**
     * @ORM\ManyToOne(targetEntity="HotelServices")
     */
    private $service;


    /**
     * @ORM\OneToMany(targetEntity="hotel\models\HotelServiceRateDetail", mappedBy="rate")
     */
    private $rateDetails;


    /**
     * @ORM\Column(type="date")
     */
    private $expiryDate;


    public function __construct(){
        $this->rateDetails = new ArrayCollection();
    }


    public function id(){
        return $this->id;
    }

    public function created(){
        return $this->created;
    }

    public function updated(){
        return $this->updated;
    }

    public function getHotel(){
        return $this->hotel;
    }

    public function setHotel($hotel){
        $this->hotel = $hotel;
    }

    public function getExpiryDate(){
        return $this->expiryDate;
    }

    public function setExpiryDate($exDate){
        $this->expiryDate = $exDate;
    }

    public function getRateDetails(){
        return $this->rateDetails;
    }

    public function addRateDetail($detail){
        $this->rateDetails[] = $detail;
    }

    public function removeRateDetail($detail){
        $this->rateDetails->removeElement($detail);
    }

    public function resetRateDetails(){
        $this->rateDetails = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }



}

