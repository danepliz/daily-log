<?php

namespace file\models;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TourFileActivityHotel extends TourFileActivity{

    /**
     * @ORM\ManyToOne(targetEntity="hotel\models\Hotel")
     */
    private $hotel;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    private $numberOfNights;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $bookingType;

    public function __construct(){
        $this->setType(TourFileActivity::FILE_ACTIVITY_TYPE_HOTEL);
    }

    public function getHotel(){
        return $this->hotel;
    }

    public function setHotel($hotel){
        $this->hotel = $hotel;
    }

    public function getNumberOfNights()
    {
        return $this->numberOfNights;
    }

    public function setNumberOfNights($numberOfNights)
    {
        $this->numberOfNights = $numberOfNights;
    }

    public function getBookingType(){
        return $this->bookingType;
    }

    public function setBookingType($bookingType){
        $this->bookingType = $bookingType;
    }

}