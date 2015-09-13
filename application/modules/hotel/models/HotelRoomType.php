<?php

namespace hotel\models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class HotelRoomType
 * @ORM\Entity(repositoryClass="HotelRepository")
 * @ORM\Table(name="ys_hotel_room_types")
 */
class HotelRoomType{
    const HOTEL_ROOM_TYPE_STATUS_ACTIVE = '1';
    const HOTEL_ROOM_TYPE_STATUS_DELETED = '2';

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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer", length=3)
     */
    private $status = self::HOTEL_ROOM_TYPE_STATUS_ACTIVE;

    public static $hotel_room_type_status = array(
        self::HOTEL_ROOM_TYPE_STATUS_DELETED => 'Deleted'
    );

    public function isDeleted()
    {
        return $this->status == self::HOTEL_ROOM_TYPE_STATUS_DELETED;
    }

    public function markAsDeleted(){
        $this->status = self::HOTEL_ROOM_TYPE_STATUS_DELETED;
    }

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
     * @ORM\Column(type="integer", length=3)
     */
    private $quantity;


    public function id(){
        return $this->id;
    }

    public function setQuantity($qty){
        $this->quantity = $qty;
    }

    public function getQuantity(){
        return $this->quantity;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getName(){
        return $this->name;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function getDescription(){
        return $this->description;
    }

    public function created(){
        return $this->created;
    }

    public function updated(){
        return $this->updated;
    }

}

