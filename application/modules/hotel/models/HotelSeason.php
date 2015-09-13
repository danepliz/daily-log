<?php

namespace hotel\models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Hotel
 * @ORM\Entity(repositoryClass="HotelRepository")
 * @ORM\Table(name="ys_hotel_seasons")
 */
class HotelSeason
{
    const HOTEL_SEASON_STATUS_ACTIVE = 'ACTIVE';
    const HOTEL_SEASON_STATUS_INACTIVE = 'INACTIVE';


    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;


    /**
     * @ORM\OneToMany(targetEntity="HotelSeasonDateRange", mappedBy="season")
     */
    private $dateRanges;

    /**
     * @ORM\ManyToOne(targetEntity="Hotel", inversedBy="seasons")
     */
    private $hotel;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status =TRUE;


    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = FALSE;

    /**
     * @return mixed
     */

    public function __construct(){
        $this->dateRanges = new ArrayCollection();
    }

    public function id()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDateRanges()
    {
        return $this->dateRanges;
    }

    public function addDateRange($range){
        $this->dateRanges[] = $range;
    }

    public function removeDateRange($range){
        $this->dateRanges->removeElement($range);
    }

    public function resetDateRanges(){
        $this->dateRanges = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getHotel()
    {
        return $this->hotel;
    }

    /**
     * @param mixed $hotel
     */
    public function setHotel($hotel)
    {
        $this->hotel = $hotel;
    }

    public function isDeleted()
    {
        return $this->deleted;
    }

    public function markAsDeleted()
    {
        $this->deleted = TRUE;
    }



}
