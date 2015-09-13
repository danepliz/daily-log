<?php

namespace agent\models;

use Gedmo\Mapping\Annotation as Gedmo;
use	Doctrine\ORM\Mapping as ORM;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="AgentRepository")
 * @ORM\Table(name="ys_agent_contact_persons")
 */
class AgentContactPerson{


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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $designation;

    /**
     * @ORM\ManyToOne(targetEntity="location\models\Country")
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="text", nullable=TRUE)
     */
    private $address;

    /**
     * @ORM\Column(type="array")
     */
    private $phones;

    /**
     * @ORM\Column(type="array")
     */
    private $emails;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $skype;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $status = TRUE;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = FALSE;

    /**
     * @ORM\ManyToOne(targetEntity="Agent")
     */
    private $agent;

    public function __toString()
    {
        return $this->name;
    }

    public function id()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = ucwords($name);
    }

    public function getDesignation()
    {
        return $this->designation;
    }

    public function setDesignation($designation)
    {
        $this->designation = $designation;
    }

    public function getCountry(){
        return $this->country;
    }

    public function setCountry($country){
        $this->country = $country;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getAddressString()
    {
        $address = [];
        if( $this->city !== ''  ){ $address[] = $this->city; }
        if( $this->address !== ''  ){ $address[] = $this->address; }
        if( $this->country ){ $address[] = $this->country->getName(); }
        return implode(', ', $address);
    }

    public function setAddress($address)
    {
        $this->address = ucwords($address);
    }

    public function getPhones()
    {
        return $this->phones;
    }

    public function setPhones($phones)
    {
        $this->phones = $phones;
    }

    public function getEmails()
    {
        return $this->emails;
    }

    public function setEmails($emails)
    {
        $this->emails = $emails;
    }

    public function isActive()
    {
        return $this->status;
    }

    public function activate()
    {
        $this->status = TRUE;
    }

    public function deactivate()
    {
        $this->status = FALSE;
    }

    public function markAsDeleted()
    {
        $this->deleted = TRUE;
    }

    public function isDeleted()
    {
        return $this->deleted;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getAgent()
    {
        return $this->agent;
    }

    public function setAgent($agent)
    {
        $this->agent = $agent;
    }

    /**
     * @return mixed
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param mixed $skype
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    }


}
    