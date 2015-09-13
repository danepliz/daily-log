<?php

namespace hotel\models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Hotel
 * @ORM\Entity(repositoryClass="HotelRateRepository")
 * @ORM\Table(name="ys_hotel_rates")
 */
class Rate{

    const RATE_TYPE_HOTEL = 'HOTEL';
    const RATE_TYPE_SERVICE = 'SERVICE';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Hotel")
     */
    private $hotel;

    /**
     * @ORM\ManyToOne(targetEntity="market\models\Market")
     */
    private $market;

    /**
     * @ORM\ManyToOne(targetEntity="HotelRoomCategory")
     */
    private $roomCategory;

    /**
     * @ORM\ManyToOne(targetEntity="HotelRoomType")
     */
    private $roomType;

    /**
     * @ORM\ManyToOne(targetEntity="HotelRoomPlan")
     */
    private $roomPlan;

    /**
     * @ORM\ManyToOne(targetEntity="HotelPackage")
     */
    private $package;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status = TRUE;

    /**
     * @ORM\Column(type="date")
     */
    private $expiryDate;

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
     * @ORM\OneToMany(targetEntity="hotel\models\RateDetail", mappedBy="rate")
     */
    private $rateDetails;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $rateStrategy = Hotel::HOTEL_RATE_VARIATION_STRATEGY_NONE;

    /**
     * @ORM\ManyToOne(targetEntity="HotelOutlet")
     */
    private $outlet;

    /**
     * @ORM\ManyToOne(targetEntity="HotelServices")
     */
    private $service;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $type;


    public function __construct(){
        $this->rateDetails = new ArrayCollection();
    }


    public function id(){
        return $this->id;
    }

    public function getHotel(){
        return $this->hotel;
    }

    public function setHotel($hotel){
        $this->hotel = $hotel;
    }

    public function getMarket(){
        return $this->market;
    }

    public function setMarket($market){
        $this->market = $market;
    }

    public function getRoomCategory(){
        return $this->roomCategory;
    }

    public function setRoomCategory($roomCategory){
        $this->roomCategory = $roomCategory;
    }

    public function getRoomPlan(){
        return $this->roomPlan;
    }

    public function setRoomPlan($roomPlan){
        $this->roomPlan = $roomPlan;
    }

    public function getRoomType(){
        return $this->roomType;
    }

    public function setRoomType($roomType){
        $this->roomType = $roomType;
    }

    public function getPackage(){
        return $this->package;
    }

    public function setPackage($package){
        $this->package = $package;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function getExpiryDate(){
        return $this->expiryDate;
    }

    public function setExpiryDate($expiryDate){
        $this->expiryDate = $expiryDate;
    }

    public function createdDate(){
        return $this->created;
    }

    public function updatedDate(){
        return $this->updated;
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

    public function getRateStrategy()
    {
        return $this->rateStrategy;
    }

    public function setRateStrategy($rateStrategy)
    {
        $this->rateStrategy = $rateStrategy;
    }

    public function getOutlet(){
        return $this->outlet;
    }

    public function setOutlet($outlet){
        $this->outlet = $outlet;
    }

    public function getService(){
        return $this->service;
    }

    public function setService($service){
        $this->service = $service;
    }

    public function setType($type){
        $this->type = $type;
    }

    public function getType(){
        return $this->type;
    }



}
