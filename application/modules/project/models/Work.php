<?php

namespace project\models;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use	Doctrine\ORM\Mapping as ORM;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="ProjectRepository")
 * @ORM\Table(name="ys_works")
 */
class Work
{
    const WORK_STATUS_TO_DO = 'TODO';
    const WORK_STATUS_DOING = 'DOING';
    const WORK_STATUS_DONE = 'APPROVED';
    const WORK_STATUS_EXPIRED = 'EXPIRED';
    const WORK_STATUS_CANCELLED = 'CANCELLED';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
     */
    private $id;
    
	 /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    
	/**
	* @ORM\Column(type="integer", length=3)
	*/
	private $status = self::WORK_STATUS_TO_DO;
	
	/**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     *
     * @ORM\ManyToOne(targetEntity="user\models\User")
     */
    private $createdBy;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;


    /**
     * @ORM\ManyToMany(targetEntity="user\models\User",cascade={"persist"})
     * @ORM\JoinTable(name="ys_work_users")
     */
    private $members;


    public static $user_status = array(
        self::WORK_STATUS_TO_DO => 'Todo',
        self::WORK_STATUS_CANCELLED => 'Cancelled',
        self::WORK_STATUS_DOING => 'Doing',
        self::WORK_STATUS_DONE => 'Done',
        self::WORK_STATUS_EXPIRED => 'Expired'
    );

    public function __construct(){
        $this->meta = new ArrayCollection();
        $this->members = new ArrayCollection();
    }
    
    public function id()
	{
	    return $this->id;
	}

    public function getCreated()
    {
        return $this->created;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($name)
    {
        $this->title = $name;
        return $this;
    }

    public function slug()
    {
        return $this->slug;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getMembers(){
        return $this->members;
    }

    public function addMember($member){
        $this->members->add($member);
        return $this;
    }

    public function resetMembers(){
        $this->members = new ArrayCollection();
        return $this;
    }

    public function removeMember($member){
        $this->members->removeElement($member);
        return $this;
    }

    public static function getStatusString($status){
        switch($status){
            case self::WORK_STATUS_TO_DO :
                $class = 'success';
                $label = 'To Do';
                break;
            case self::WORK_STATUS_CANCELLED :
                $class = 'primary';
                $label = 'Cancelled';
                break;
            case self::WORK_STATUS_EXPIRED :
                $class = 'danger';
                $label = 'Expired';
                break;
            case self::WORK_STATUS_DOING :
                $class = 'warning';
                $label = 'Doing';
                break;
            case self::WORK_STATUS_DONE :
                $class = 'success';
                $label = 'Done';
                break;
            default :
                $class = 'default';
                $label = 'undefined';
                break;
        }

        return "<span class='label label-{$class}'>{$label}</span>";
    }


}