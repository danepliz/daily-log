<?php

namespace file\models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
 class TourFileActivityDetailHotel extends TourFileActivityDetail{

     /**
      * @ORM\ManyToOne(targetEntity="hotel\models\HotelRoomCategory")
      */
     private $roomCategory;

     /**
      * @ORM\ManyToOne(targetEntity="hotel\models\HotelRoomType")
      */
     private $roomType;

     /**
      * @ORM\Column(type="string", length=255, nullable=true)
      */
     private $nickNameForRoomType;

     /**
      * @ORM\ManyToOne(targetEntity="hotel\models\HotelRoomPlan")
      */
     private $roomPlan;

     /**
      * @ORM\Column(type="integer", length=10)
      */
     private $numberOfRooms;

     /**
      * @ORM\ManyToOne(targetEntity="hotel\models\Rate")
      */
     private $hotelRate;

     /**
      * @ORM\ManyToOne(targetEntity="hotel\models\RateDetail")
      */
     private $hotelRateDetail;

     /**
      * @ORM\ManyToOne(targetEntity="hotel\models\HotelPackage")
      */
     private $package;

     /**
      * @ORM\Column(type="text", nullable=true)
      */
     private $note;

     /**
      * @ORM\Column(type="integer", length=10, nullable=true)
      */
     private $extraBed = 0;

     /**
      * @ORM\ManyToOne(targetEntity="hotel\models\HotelServices")
      */
     private $service;

     /**
      * @ORM\ManyToOne(targetEntity="hotel\models\HotelOutlet")
      */
     private $outlet;


     public function __construct(){
         $this->setTourType(TourFileActivity::FILE_ACTIVITY_TYPE_HOTEL);
     }

     public function setNote($note){
         $this->note = $note;
     }

     public function getNote(){
         return $this->note;
     }

     public function getNumberOfRooms()
     {
         return $this->numberOfRooms;
     }

     public function setNumberOfRooms($numberOfRooms)
     {
         $this->numberOfRooms = $numberOfRooms;
     }

     public function getRoomCategory()
     {
         return $this->roomCategory;
     }

     public function setRoomCategory($roomCategory)
     {
         $this->roomCategory = $roomCategory;
     }

     public function getRoomPlan()
     {
         return $this->roomPlan;
     }

     public function setRoomPlan($roomPlan)
     {
         $this->roomPlan = $roomPlan;
     }

     public function getRoomType()
     {
         return $this->roomType;
     }

     public function setRoomType($roomType)
     {
         $this->roomType = $roomType;
     }

     public function getHotelRate()
     {
         return $this->hotelRate;
     }

     public function setHotelRate($hotelRate)
     {
         $this->hotelRate = $hotelRate;
     }

     public function getExtraBed()
     {
         return $this->extraBed;
     }

     public function setExtraBed($extraBed)
     {
         $this->extraBed = $extraBed;
     }

     public function getHotelRateDetail()
     {
         return $this->hotelRateDetail;
     }

     public function setHotelRateDetail($hotelRateDetail)
     {
         $this->hotelRateDetail = $hotelRateDetail;
     }

     public function getPackage()
     {
         return $this->package;
     }

     public function setPackage($package)
     {
         $this->package = $package;
     }

     public function getService()
     {
         return $this->service;
     }

     public function setService($service)
     {
         $this->service = $service;
     }

     public function getOutlet()
     {
         return $this->outlet;
     }

     public function setOutlet($outlet)
     {
         $this->outlet = $outlet;
     }

     public function getNickNameForRoomType()
     {
         return $this->nickNameForRoomType;
     }

     public function setNickNameForRoomType($nickNameForRoomType)
     {
         $this->nickNameForRoomType = $nickNameForRoomType;
     }

     public function getRoomTypeString(){
         if($this->nickNameForRoomType == ''){
             return ( $this->roomType )? $this->roomType->getName() : '';
         }
         return $this->nickNameForRoomType;
     }









 }