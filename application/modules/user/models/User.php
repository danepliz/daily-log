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
        $this->setSalt($hashed['salt'])
            ->setSecrete($hashed['hash']);
	}

    public function setSalt($salt){
        $this->salt = $salt;

        return $this;
    }

    public function getSalt(){
        return $this->salt;
    }

    public function setToken(){
        $this->token = md5(microtime());

        return $this;
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

        return $this;
	}

	public function getAddress()
	{
	    return $this->address;
	}

	public function setAddress($address)
	{
	    $this->address = $address;

        return $this;
	}

	public function getPhone()
	{
	    return $this->phone;
	}

	public function setPhone($phone)
	{
	    $this->phone = $phone;

        return $this;
	}

	public function getMobile()
	{
	    return $this->mobile;
	}

	public function setMobile($mobile)
	{
	    $this->mobile = $mobile;

        return $this;
	}

	public function getEmail()
	{
	    return $this->email;
	}

	public function setEmail($email)
	{
	    $this->email = $email;

        return $this;
	}

	public function isActive()
	{
	    return $this->status == self::USER_STATUS_ACTIVE;
	}

	public function activate()
	{
	    $this->status = self::USER_STATUS_ACTIVE;

        return $this;
	}
	
	public function deactivate(){
		$this->status = self::USER_STATUS_BLOCKED;

        return $this;
	}

    public function isDeleted()
    {
        return $this->status == self::USER_STATUS_DELETED;
    }

    public function markAsDeleted(){
        $this->status = self::USER_STATUS_DELETED;

        return $this;
    }

	public function getGroup()
	{
	    return $this->group;
	}

	public function setGroup(Group $group)
	{
	    $this->group = $group;

        return $this;
	}

	public function getCreated()
	{
	    return $this->created;
	}

    public function getApiKey(){
    	return $this->api_key;
    }

    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;

        return $this;
    }
    
    public function isFirstLogin()
    {
    	return $this->first_login;
    }
    
    public function unMarkFirstLogin()
    {
    	$this->first_login = FALSE;

        return $this;
    }
    
    public function markFirstLogin()
    {
    	$this->first_login = TRUE;

        return $this;
    }
    
    public function pwdLastChangedOn()
    {
    	return $this->pwd_change_on;
    }
    
    public function setPwdLastChangedOn()
    {
    	$this->pwd_change_on = new \DateTime();

        return $this;
    }
    
    public function setLastLogged()
    {
    	$this->last_logged = new \DateTime();

        return $this;
    }
    
    public function getLastLogged()
    {
    	return $this->last_logged;
    }

    public function unsetAgent()
    {
    	$this->agent = NULL;

        return $this;
    }

    public function getResetToken()
    {
        return $this->resetToken;
    }

    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getTokenRequested()
    {
        return $this->tokenRequested;
    }

    public function setTokenRequested($tokenRequested)
    {
        $this->tokenRequested = $tokenRequested;

        return $this;
    }

    public function getTokenUsed()
    {
        return $this->tokenUsed;
    }

    public function setTokenUsed($tokenUsed)
    {
        $this->tokenUsed = $tokenUsed;

        return $this;
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    public function getGravatar( $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $this->email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

}