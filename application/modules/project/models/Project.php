<?php

namespace project\models;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use	Doctrine\ORM\Mapping as ORM;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="ProjectRepository")
 * @ORM\Table(name="ys_projects")
 */
class Project
{
    const PROJECT_STATUS_ACTIVE = 1;
    const PROJECT_STATUS_BLOCKED = 2;
    const PROJECT_STATUS_DELETED = 3;
    const PROJECT_STATUS_HANDLED = 4;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
     */
    private $id;
    
	 /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
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
	private $status = self::PROJECT_STATUS_ACTIVE;
	
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
     * @ORM\OneToMany(targetEntity="project\models\ProjectMeta", mappedBy="project")
     */
    private $meta;

    /**
     * @ORM\ManyToMany(targetEntity="user\models\User",cascade={"persist"})
     * @ORM\JoinTable(name="ys_project_members")
     */
    private $members;

    /**
     * @ORM\OneToMany(targetEntity="project\models\ProjectAttachment", mappedBy="project")
     */
    private $attachments;


    public static $user_status = array(
        self::PROJECT_STATUS_ACTIVE => 'Active',
        self::PROJECT_STATUS_BLOCKED => 'Blocked',
        self::PROJECT_STATUS_DELETED => 'Deleted',
        self::PROJECT_STATUS_HANDLED => 'Handled'
    );

    public function __construct(){
        $this->meta = new ArrayCollection();
        $this->members = new ArrayCollection();
        $this->attachments = new ArrayCollection();
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
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

    public function isActive(){
        return $this->status == self::PROJECT_STATUS_ACTIVE;
    }

    public function getMeta(){
        return $this->meta;
    }

    public function addMeta($meta){
        $this->meta->add($meta);
        return $this;
    }

    public function resetMeta(){
        $this->meta = new ArrayCollection();
        return $this;
    }

    public function removeMeta($meta){
        $this->meta->removeElement($meta);
        return $this;
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
            case self::PROJECT_STATUS_ACTIVE :
                $class = 'success';
                $label = 'active';
                break;
            case self::PROJECT_STATUS_HANDLED :
                $class = 'primary';
                $label = 'handled';
                break;
            case self::PROJECT_STATUS_DELETED :
                $class = 'danger';
                $label = 'deleted';
                break;
            case self::PROJECT_STATUS_BLOCKED :
                $class = 'warning';
                $label = 'blocked';
                break;
            default :
                $class = 'default';
                $label = 'undefined';
                break;
        }

        return "<span class='label label-{$class}'>{$label}</span>";
    }

    public function getStatusAsString(){
        return self::getStatusString($this->getStatus());
    }

    public function getAttachments(){
        return $this->attachments;
    }

    public function addAttachment($attachment){
        $this->attachments->add($attachment);
        return $this;
    }

    public function removeAttachement($attachment){
        $this->attachments->removeElement($attachment);
        return $this;
    }

    public function resetAttachments(){
        $this->attachments = new ArrayCollection();
    }





}