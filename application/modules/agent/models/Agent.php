<?php 

namespace agent\models;

use Gedmo\Mapping\Annotation as Gedmo;
use	Doctrine\ORM\Mapping as ORM;
use	Doctrine\Common\Collections\ArrayCollection;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="AgentRepository")
 * @ORM\Table(name="ys_agents")
 */
class Agent{
	
	const STATUS_AGENT_BLOCKED = 0;
	const STATUS_AGENT_ACTIVE = 1;
	const STATUS_AGENT_DELETED = 2;
//    const STATUS_AGENT_DELETED = 2;
	
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
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255, nullable=false,unique = true)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="location\models\Country")
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email2;
    
    /**
     * @ORM\Column(type="text", nullable=TRUE)
     */
    private $address;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $pobox;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $skype;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $status = self::STATUS_AGENT_ACTIVE;
    
    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
    * @ORM\ManyToOne(targetEntity="user\models\User")
    */
     private $createdBy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = FALSE;

    /**
     * @ORM\OneToMany(targetEntity="AgentContactPerson", mappedBy="agent")
     */
    private $contactPersons;

    /**
     * @ORM\ManyToMany(targetEntity="user\models\User",cascade={"persist"})
     * @ORM\JoinTable(name="ys_agent_permitted_users")
     */
    private $permittedUsers;


    public static $status_desc = array(
        self::STATUS_AGENT_ACTIVE => 'Active',
        self::STATUS_AGENT_BLOCKED =>'Blocked',
        self::STATUS_AGENT_DELETED =>'Deleted'
//        self::STATUS_AGENT_DELETED =>'Deleted'
    );

    public function __construct(){
        $this->contactPersons = new ArrayCollection();
        $this->permittedUsers = new ArrayCollection();
    }

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

	public function getSlug()
	{
		return $this->slug;
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
	
	public function getPhone1()
	{
		return $this->phone1;
	}
	
	public function setPhone1($phone1)
	{
		$this->phone1 = $phone1;
	}

    public function getPhone2()
    {
        return $this->phone2;
    }

    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;
    }
	
	public function getFax()
	{
		return $this->fax;
	}
	
	public function setFax($fax)
	{
		$this->fax = $fax;
	}
	
	public function getEmail1()
	{
		return $this->email1;
	}
	
	public function setEmail1($email1)
	{
		$this->email1 = $email1;
	}

    public function getEmail2()
    {
        return $this->email2;
    }

    public function setEmail2($email2)
    {
        $this->email2 = $email2;
    }

    public function getPOBox()
    {
        return $this->pobox;
    }

    public function setPOBox($pobox)
    {
        $this->pobox = $pobox;
    }
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function setDescription($description)
	{
	    $this->description = $description;
	}

	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}

    public function getWebsite1(){
        return $this->website1;
    }

    public function setWebsite1($website1){
        $this->website1 = $website1;
    }

    public function getWebsite2(){
        return $this->website2;
    }

    public function setWebsite2($website2){
        $this->website2 = $website2;
    }

    public function isDeleted()
    {
        return $this->status == self::STATUS_AGENT_DELETED;
    }

    public function markAsDeleted(){
        $this->status = self::STATUS_AGENT_DELETED;
    }

    public function markAsUnDeleted(){
        $this->status = self::STATUS_AGENT_ACTIVE;
    }
	
	public function getCreated()
	{
		return $this->created;
	}

    public function getContactPersons(){
        return $this->contactPersons;
    }

    public function addContactPerson($person){
        $this->contactPersons[] = $person;
    }

    public function removeContactPerson($person){
        $this->contactPersons->removeElement($person);
    }

    public function removeAllContactPersons(){
        $this->contactPersons = new ArrayCollection();
    }
	
	public function getFullAddress()
	{
//		$address = $this->address.', '.$this->city->getName().', '.$this->city->getState()->getName();
		return $this->address;
	}

    public function isActive()
    {
        return $this->status == self::STATUS_AGENT_ACTIVE;
    }
	
	public function getStatusString()
    {
        return self::$status_desc[$this->status];
    }

    public function activate(){
        $this->status = self::STATUS_AGENT_ACTIVE;
    }

    public function deactivate(){
        $this->status = self::STATUS_AGENT_BLOCKED;
    }

    public function getSkype()
    {
        return $this->skype;
    }

    public function setSkype($skype)
    {
        $this->skype = $skype;
    }

    public function getDeleted()
    {
        return $this->deleted;
    }

    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
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


}
    