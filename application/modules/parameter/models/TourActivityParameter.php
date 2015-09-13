<?php

namespace parameter\models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class TourActivityParameter
 * @ORM\Entity(repositoryClass="TourActivityParameterRepository")
 * @ORM\Table(name="ys_tour_activity_parameters")
 */
class TourActivityParameter{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $travelXO = FALSE;

    /**
     * @ORM\Column(type="boolean")
     */
    private $transportXO = FALSE;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hotelXO = FALSE;

    /**
     * @ORM\Column(type="boolean")
     */
    private $entranceXO = FALSE;

    /**
     * @ORM\Column(type="boolean")
     */
    private $otherXO = FALSE;

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


    public function __construct(){
    }

    public function id(){
        return $this->id;
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

    public function isSelectedTravelXO(){
        return $this->travelXO;
    }

    public function selectTravelXO(){
        $this->travelXO = TRUE;
    }

    public function unSelectTravelXO(){
        $this->travelXO = FALSE;
    }

    public function isSelectedTransportXO(){
        return $this->transportXO;
    }

    public function selectTransportXO(){
        $this->transportXO = TRUE;
    }

    public function unSelectTransportXO(){
        $this->transportXO = FALSE;
    }

    public function isSelectedHotelXO(){
        return $this->hotelXO;
    }

    public function selectHotelXO(){
        $this->hotelXO = TRUE;
    }

    public function unSelectHotelXO(){
        $this->hotelXO = FALSE;
    }

    public function isSelectedEntranceXO(){
        return $this->entranceXO;
    }

    public function selectEntranceXO(){
        $this->entranceXO = TRUE;
    }

    public function unSelectEntranceXO(){
        $this->entranceXO = FALSE;
    }

    public function isSelectedOtherXO(){
        return $this->otherXO;
    }

    public function selectOtherXO(){
        $this->otherXO = TRUE;
    }

    public function unSelectOtherXO(){
        $this->otherXO = FALSE;
    }


}

