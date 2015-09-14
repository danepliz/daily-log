<?php

namespace user\models;

use Gedmo\Mapping\Annotation as Gedmo;
use	Doctrine\ORM\Mapping as ORM;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Table(name="ys_users")
 */
class User
{
    const USER_STATUS_ACTIVE = 1;
    const USER_STATUS_BLOCKED = 2;
    const USER_STATUS_DELETED = 3;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $salt;
    
    /**
     * @ORM\Column(type="string",length=255)
     */
    private $secrete;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $token;
    
	 /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullname;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;
    
    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mobile;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;
    
	/**
	* @ORM\Column(type="integer", length=3)
	*/
	private $status = self::USER_STATUS_ACTIVE;
	
	/**
     * @ORM\ManyToOne(targetEntity="Group")
     */
    private $group;
	
	/**
     * @var datetime $created
     *
     * @gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=6, unique=true)
     */
    private $api_key;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $first_login = TRUE;
    
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $pwd_change_on;
    
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $last_logged;
    
    /**
     * 
     * @var string 
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $resetToken;
    
    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @var \DateTime
     */
    private $tokenRequested;
    
    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @var \DateTime
     */
    private $tokenUsed;


    public static $user_status = array(
        self::USER_STATUS_ACTIVE => 'Active',
        self::USER_STATUS_BLOCKED => 'Blocked',
        self::USER_STATUS_DELETED => 'Deleted'
    );

    public function __construct(){
        $this->token = md5('token'. microtime());
    }
    
    public function id()
	{
	    return $this->id;
	}

	public function getSecrete()
	{
	    return $this->secrete;
	}

    public function setSecrete($sec){
        $this->secrete = $sec;
    }

	public function setPassword($password)
	{
        $CI = \CI::$APP;
        $CI->load->library('password');
        $hashed = $CI->password->create_hash($password);
        $this->setSalt($hashed['salt']);
	    $this->setSecrete($hashed['hash']);
	}

    public function setSalt($salt){
        $this->salt = $salt;
    }

    public function getSalt(){
        return $this->salt;
    }

    public function setToken(){
        $this->token = md5(microtime());
    }

    public function getToken(){
        return $this->token;
    }

	public function getFullName()
	{
	    return $this->fullname;
	}

	public function setFullName($fullName)
	{
	    $this->fullname = ucwords($fullName);
	}

	public function getAddress()
	{
	    return $this->address;
	}

	public function setAddress($address)
	{
	    $this->address = $address;
	}

	public function getPhone()
	{
	    return $this->phone;
	}

	public function setPhone($phone)
	{
	    $this->phone = $phone;
	}

	public function getMobile()
	{
	    return $this->mobile;
	}

	public function setMobile($mobile)
	{
	    $this->mobile = $mobile;
	}

	public function getEmail()
	{
	    return $this->email;
	}

	public function setEmail($email)
	{
	    $this->email = $email;
	}

	public function isActive()
	{
	    return $this->status == self::USER_STATUS_ACTIVE;
	}

	public function activate()
	{
	    $this->status = self::USER_STATUS_ACTIVE;
	}
	
	public function deactivate(){
		$this->status = self::USER_STATUS_BLOCKED;
	}

    public function isDeleted()
    {
        return $this->status == self::USER_STATUS_DELETED;
    }

    public function markAsDeleted(){
        $this->status = self::USER_STATUS_DELETED;
    }

	public function getGroup()
	{
	    return $this->group;
	}

	public function setGroup(Group $group)
	{
	    $this->group = $group;
	}

	public function getCreated()
	{
	    return $this->created;
	}

    public function getApiKey(){
    	return $this->api_key;
    }

    public function setApiKey($api_key){
    	
    	$this->api_key = $api_key;
    }
    
    public function isFirstLogin(){
    	return $this->first_login;
    }
    
    public function unMarkFirstLogin(){
    	$this->first_login = FALSE;	
    }
    
    public function markFirstLogin(){
    	$this->first_login = TRUE;
    }
    
    public function pwdLastChangedOn(){
    	return $this->pwd_change_on;
    }
    
    public function setPwdLastChangedOn(){
    	$this->pwd_change_on = new \DateTime();
    }
    
    public function setLastLogged(){
    	$this->last_logged = new \DateTime();
    }
    
    public function getLastLogged(){
    	return $this->last_logged;
    }

    public function unsetAgent(){
    	$this->agent = NULL;
    }

    public function getResetToken()
    {
        return $this->resetToken;
    }

    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;
    }

    public function getTokenRequested()
    {
        return $this->tokenRequested;
    }

    public function setTokenRequested($tokenRequested)
    {
        $this->tokenRequested = $tokenRequested;
    }

    public function getTokenUsed()
    {
        return $this->tokenUsed;
    }

    public function setTokenUsed($tokenUsed)
    {
        $this->tokenUsed = $tokenUsed;
    }

}