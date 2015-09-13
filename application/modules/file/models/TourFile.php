<?php

namespace file\models;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class TourFile
 * @ORM\Entity(repositoryClass="TourFileRepository")
 * @ORM\Table(name="ys_tour_files")
 */
class TourFile{

    const TOUR_FILE_STATUS_ACTIVE = 'ACTIVE';
    const TOUR_FILE_STATUS_VOID = 'VOID';
    const TOUR_FILE_STATUS_DELETED = 'DELETED';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $tourNumber;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $fileNumber;

    /**
     * @ORM\ManyToOne(targetEntity="agent\models\Agent")
     */
    private $agent;

    /**
     * @ORM\ManyToOne(targetEntity="agent\models\AgentContactPerson")
     */
    private $agentContactPerson;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $client;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $numberOfPax;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $numberOfChildren;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $numberOfInfants;

    /**
     * @ORM\ManyToOne(targetEntity="location\models\Country")
     */
    private $nationality;

    /**
     * @ORM\ManyToOne(targetEntity="market\models\Market")
     */
    private $market;

    /**
     * @ORM\Column(type="text")
     */
    private $instructions;

    /**
     * @ORM\OneToMany(targetEntity="TourFileActivity", mappedBy="tourFile")
     */
    private $activities;

    /**
     * @ORM\ManyToOne(targetEntity="user\models\User")
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="user\models\User")
     */
    private $updatedBy;

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
     * @ORM\ManyToOne(targetEntity="user\models\User")
     */
    private $tourOfficer;

    /**
     * @ORM\ManyToMany(targetEntity="user\models\User",cascade={"persist"})
     * @ORM\JoinTable(name="ys_tour_file_permitted_users")
     */
    private $permittedUsers;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $status = self::TOUR_FILE_STATUS_ACTIVE;

    public function __construct(){
        $this->activities = new ArrayCollection();
        $this->permittedUsers = new ArrayCollection();
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

    public function getAgent()
    {
        return $this->agent;
    }

    public function setAgent($agent)
    {
        $this->agent = $agent;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setClient($client)
    {
        $this->client = $client;
    }

    public function getActivities()
    {
        return $this->activities;
    }

    public function addActivity($detail){
        $this->activities[] = $detail;
    }

    public function removeActivity($detail){
        $this->activities->removeElement($detail);
    }

    public function resetActivities(){
        $this->activities = new ArrayCollection();
    }

    public function getFileNumber()
    {
        return $this->fileNumber;
    }

    public function setFileNumber($fileNumber)
    {
        $this->fileNumber = $fileNumber;
    }

    public function getInstructions()
    {
        return $this->instructions;
    }

    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;
    }

    public function getMarket()
    {
        return $this->market;
    }

    public function setMarket($market)
    {
        $this->market = $market;
    }

    public function getNationality()
    {
        return $this->nationality;
    }

    public function setNationality($nationality)
    {
        $this->nationality = $nationality;
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

    public function getTourNumber()
    {
        return $this->tourNumber;
    }

    public function setTourNumber($tourNumber)
    {
        $this->tourNumber = $tourNumber;
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

    public function setAgentContactPerson($person){
        $this->agentContactPerson = $person;
    }

    public function getAgentContactPerson(){
        return $this->agentContactPerson;
    }

    public function setTourOfficer($officer){
        $this->tourOfficer = $officer;
    }

    public function getTourOfficer(){
        return $this->tourOfficer;
    }

    public function getPermittedUsers(){
        return $this->permittedUsers;
    }

    public function addPermittedUser($user){
        $this->permittedUsers[] = $user;
    }

    public function removePermittedUser($user){
        $this->permittedUsers->removeElement($user);
    }

    public function resetPermittedUsers(){
        $this->permittedUsers = new ArrayCollection();
    }

    public function getStatus(){
        return $this->status;
    }

    public function isActive(){
        return $this->status == self::TOUR_FILE_STATUS_ACTIVE;
    }

    public function markAsVoid(){
        $this->status = self::TOUR_FILE_STATUS_VOID;
    }

    public function markAsDeleted(){
        $this->status = self::TOUR_FILE_STATUS_DELETED;
    }

    public function markAsActive(){
        $this->status = self::TOUR_FILE_STATUS_ACTIVE;
    }



}

