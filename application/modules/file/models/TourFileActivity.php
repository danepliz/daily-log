<?php

namespace file\models;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * @ORM\Entity(repositoryClass="file\models\TourFileActivityRepository")
 * @ORM\Table(name="ys_tour_file_activities")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"hotel" = "file\models\TourFileActivityHotel"})
 */
class TourFileActivity{

    const FILE_ACTIVITY_TYPE_HOTEL = '301';
    const FILE_ACTIVITY_TYPE_TRAVEL = '302';
    const TOUR_FILE_STATUS_DELETED = '2';
    const TOUR_FILE_STATUS_ACTIVE = '1';

    const ACTIVITY_STATUS_ACTIVE = 1;
    const ACTIVITY_STATUS_VOID = 2;
    const ACTIVITY_STATUS_DELETED = 3;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=4)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $arrivalDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $departureDate;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    private $numberOfPax;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    private $numberOfChildren;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    private $numberOfInfants;

    /**
     * @ORM\OneToMany(targetEntity="TourFileActivityDetail", mappedBy="tourActivity")
     */
    private $details;

    /**
     * @ORM\ManyToOne(targetEntity="TourFile")
     */
    private $tourFile;

    /**
     * @ORM\ManyToOne(targetEntity="user\models\User")
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="user\models\User")
     */
    private $updatedBy;

    /**
     * @ORM\ManyToOne(targetEntity="file\models\TourFileActivity")
     */
    private $parentActivity;

    /**
     * @ORM\OneToMany(targetEntity="file\models\TourFileActivity", mappedBy="parentActivity")
     */
    private $childActivities;

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
     * @ORM\Column(type="time", nullable=true)
     */
    private $arrivalTime;


    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $departureTime;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $arrivalDepartureNote;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $arrivalNote;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $departureNote;

    /**
     * @ORM\Column(type="string",length=50, nullable=true)
     */
    private $arrivalMode;

    /**
     * @ORM\Column(type="string",length=50, nullable=true)
     */
    private $departureMode;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $confirmationNumber;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isXoGenerated = FALSE;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $xoNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $xoCreatedDate;

    /**
     * @ORM\ManyToOne(targetEntity="user\models\User")
     */
    private $xoGeneratedBy;

    /**
     * @ORM\ManyToOne(targetEntity="currency\models\Currency")
     */
    private $currency;

    /**
     * @ORM\Column(type="integer", length=5, nullable=true)
     */
    private $revertedTimes;

    /**
     * @ORM\Column(type="integer", length=5, nullable=true)
     */
    private $status = self::ACTIVITY_STATUS_ACTIVE;

    /**
     * @ORM\ManyToOne(targetEntity="market\models\Market")
     */
    private $market;


    const ARRIVAL_MODE_SURFACE = 'SURFACE';
    const ARRIVAL_MODE_FLIGHT = 'FLIGHT';

    public static $arrival_modes = array(
        self::ARRIVAL_MODE_SURFACE => 'Surface',
        self::ARRIVAL_MODE_FLIGHT => 'Flight'
    );

    public static $activity_status = array(
        self::ACTIVITY_STATUS_ACTIVE => 'ACTIVE',
        self::ACTIVITY_STATUS_DELETED => 'INACTIVE',
        self::ACTIVITY_STATUS_VOID=>'VOID'
    );

    public static $tour_file_status = array(
        self::TOUR_FILE_STATUS_DELETED => 'Deleted'
    );

    public static $fileActivityTypes = [
        self::FILE_ACTIVITY_TYPE_HOTEL => 'HOTEL',
//        self::FILE_ACTIVITY_TYPE_TRAVEL => 'TRAVEL',
    ];

    public function __construct(){
        $this->details = new ArrayCollection();
    }

    public function id(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getXoGeneratedBy(){
        return $this->xoGeneratedBy;
    }

    public function setXoGeneratedBy($user){
        $this->xoGeneratedBy = $user;
    }

    public function getXoNumber(){
        return $this->xoNumber;
    }

    public function setXoNumber($xoNumber){
        $this->xoNumber = $xoNumber;
    }

    public function getXoCreatedDate()
    {
        return $this->xoCreatedDate;
    }

    public function setXoCreatedDate($xoCreatedDate)
    {
        $this->xoCreatedDate = $xoCreatedDate;
    }

    public function getConfirmationNumber()
    {
        return $this->confirmationNumber;
    }

    public function setConfirmationNumber($confirmationNumber)
    {
        $this->confirmationNumber = $confirmationNumber;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function created(){
        return $this->created;
    }

    public function updated(){
        return $this->updated;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function addDetail($detail){
        $this->details[] = $detail;
    }

    public function removeDetail($detail){
        $this->details->removeElement($detail);
    }

    public function resetDetails(){
        $this->details = new ArrayCollection();
    }

    public function getNumberOfChildren()
    {
        return $this->numberOfChildren;
    }

    public function setNumberOfChildren($numberOfChildren)
    {
        $this->numberOfChildren = $numberOfChildren;
    }

    public function getNumberOfInfants()
    {
        return $this->numberOfInfants;
    }

    public function setNumberOfInfants($numberOfInfants)
    {
        $this->numberOfInfants = $numberOfInfants;
    }

    public function getNumberOfPax()
    {
        return $this->numberOfPax;
    }

    public function setNumberOfPax($numberOfPax)
    {
        $this->numberOfPax = $numberOfPax;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    public function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate($arrivalDate)
    {
        $this->arrivalDate = $arrivalDate;
    }

    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    public function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getTourFile()
    {
        return $this->tourFile;
    }

    public function setTourFile($tourFile)
    {
        $this->tourFile = $tourFile;
    }

    public function getDepartureTime()
    {
        return $this->departureTime;
    }

    public function setDepartureTime($departureTime)
    {
        $this->departureTime = $departureTime;
    }

    public function getArrivalTime()
    {
        return $this->arrivalTime;
    }

    public function setArrivalTime($arrivalTime)
    {
        $this->arrivalTime = $arrivalTime;
    }

    public function getArrivalDepartureNote()
    {
        return $this->arrivalDepartureNote;
    }

    public function setArrivalDepartureNote($arrivalDepartureNote)
    {
        $this->arrivalDepartureNote = $arrivalDepartureNote;
    }

    public function isXoGenerated(){
        return $this->isXoGenerated;
    }

    public function markAsXoGenerated(){
        $this->isXoGenerated = TRUE;
    }

    public function markAsXoNotGenerated(){
        $this->isXoGenerated = FALSE;
    }

    public function setArrivalNote($arrivalNote){
        $this->arrivalNote = $arrivalNote;
    }

    public function getArrivalNote(){
        return $this->arrivalNote;
    }

    public function setDepartureNote($departureNote){
        $this->departureNote = $departureNote;
    }

    public function getDepartureNote(){
        return $this->departureNote;
    }

    public static function getArrivalModesDesc()
    {
        return self::$arrival_modes;
    }

    public function getArrivalMode()
    {
        return $this->arrivalMode;
    }

    public function setArrivalMode($arrivalMode)
    {
        $this->arrivalMode = $arrivalMode;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency(\currency\models\Currency $currency)
    {
        $this->currency = $currency;
    }

    public function getDepartureMode()
    {
        return $this->departureMode;
    }

    public function setDepartureMode($departureMode)
    {
        $this->departureMode = $departureMode;
    }

    public function getRevertedTimes()
    {
        return $this->revertedTimes;
    }

    public function setRevertedTimes($revertedTimes)
    {
        $this->revertedTimes = $revertedTimes;
    }

    public function increaseRevertedTimes()
    {
        $time = ( $this->revertedTimes == '' )? 0 : $this->revertedTimes;
        $this->revertedTimes = $time + 1;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function isActive(){
        return $this->status == self::ACTIVITY_STATUS_ACTIVE;
    }

    public function isDeleted(){
        return $this->status == self::ACTIVITY_STATUS_DELETED;
    }

    public function markAsVoid(){
        $this->status = self::ACTIVITY_STATUS_VOID;
    }

    public function markAsDeleted(){
        $this->status = self::ACTIVITY_STATUS_DELETED;
    }

    public function markAsActive(){
        $this->status = self::ACTIVITY_STATUS_ACTIVE;
    }

    public function getChildActivities()
    {
        return $this->childActivities;
    }

    public function addChild($child){
        $this->childActivities[] = $child;
    }

    public function getParentActivity()
    {
        return $this->parentActivity;
    }

    public function setParentActivity($parentActivity)
    {
        $this->parentActivity = $parentActivity;
    }

    public function getMarket()
    {
        return $this->market;
    }

    public function setMarket($market)
    {
        $this->market = $market;
    }


}

