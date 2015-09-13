<?php

namespace user\models;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use	Doctrine\Common\Collections\ArrayCollection;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="GroupRepository")
 * @ORM\Table(name="ys_groups")
 */
class Group
{
	const SUPER_ADMIN = 1;
	const USER_GROUP_ACCOUNTANT = 2;
	const USER_GROUP_TOUR_OFFICER = 3;
	const USER_GROUP_CORRESPONDANCE = 4;

	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
     */
    private $id;

	 /**
     * @ORM\Column(type="string", length=255, nullable=false,unique=true)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;
	
	/**
	* @ORM\Column(type="boolean")
	*/
	private $active = TRUE;
	
	/**
     * @var datetime $created
     *
     * @gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;
	
	/**
     * @ORM\OneToMany(targetEntity="User", mappedBy="group")
     */
    private $users;
	
    /**
     * @ORM\ManyToMany(targetEntity="Permission",cascade={"persist"})
     * @ORM\JoinTable(name="ys_group_permission")
     */
    private $permissions;
    
    /**
     * @ORM\Column(type="boolean") 
     */
    private $mtoOnly = FALSE;
    
    
    public function __construct(){
    	$this->permissions = new ArrayCollection();
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

	public function getDescription()
	{
	    return $this->description;
	}

	public function setDescription($description)
	{
	    $this->description = $description;
	}

	public function getActive()
	{
	    return $this->active;
	}

	public function setActive($active)
	{
	    $this->active = $active;
	}

	public function getCreated()
	{
	    return $this->created;
	}

	public function getUsers()
	{
	    return $this->users;
	}
	
	public function getPermissions(){
		return $this->permissions;
	}
	
	public function clonePermissions(){
		$permissions = $this->permissions;
		$this->permissions = new ArrayCollection();
		
		foreach($permissions as $p){
// 			$permissionClone = clone $p;
			$this->permissions->add($p);
		}
	}
	
	public function addPermission(Permission $permission){
		$this->permissions[] = $permission;
	}
	
	public function removePermission(Permission $permission){
		$this->permissions->removeElement($permission);
	}
	
	public function resetPermissions(){
		$this->permissions = new ArrayCollection();
	}

    public function isMtoOnly()
    {
        return $this->mtoOnly;
    }

    public function disableMtoOnly($mtoOnly)
    {
        $this->mtoOnly = FALSE;
    }
    
    public function forMtoOnly()
    {
    	$this->mtoOnly = TRUE;
    }
}