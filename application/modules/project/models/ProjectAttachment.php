<?php

namespace project\models;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use	Doctrine\ORM\Mapping as ORM;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="ProjectRepository")
 * @ORM\Table(name="ys_project_attachments")
 */
class ProjectAttachment
{
    const PROJECT_ATTACHMENT_TYPE_IMAGE = 1;
    const PROJECT_ATTACHMENT_TYPE_AUDIO = 2;
    const PROJECT_ATTACHMENT_TYPE_VIDEO = 3;
    const PROJECT_ATTACHMENT_TYPE_DOCUMENT = 4;
//    const PROJECT_ATTACHMENT_TYPE_DOCUMENT = 5;

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
	private $type = self::PROJECT_ATTACHMENT_TYPE_DOCUMENT;
	
	/**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $uploadedTime;

    /**
     *
     * @ORM\ManyToOne(targetEntity="user\models\User")
     */
    private $uploadedBy;

    /**
     * @var boolean $deleted
     *
     * @ORM\Column(type="boolean")
     */
    private $deleted = FALSE;

    /**
     * @var user
     *
     * @ORM\ManyToOne(targetEntity="user\models\User")
     */
    private $deletedBy;

    /**
     * @var string $extension
     *
     * @ORM\Column(type="string", length=10, nullable=false)
     */
    private $extension;

    /**
     * @var project $project
     *
     * @ORM\ManyToOne(targetEntity="project\models\Project", inversedBy="attachments")
     */
    private $project;

    
    public function id()
	{
	    return $this->id;
	}

    public function slug(){
        return $this->slug;
    }


    public function markAsDeleted()
    {
        $this->deleted = TRUE;
        return $this;
    }

    public function markAsNotDeleted(){
        $this->deleted = FALSE;
        return $this;
    }

    public function isDeleted()
    {
        return $this->deleted;
    }

    public function setDeletedBy($deletedBy)
    {
        $this->deletedBy = $deletedBy;
        return $this;
    }

    public function getDeletedBy()
    {
        return $this->deletedBy;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setUploadedBy($uploadedBy)
    {
        $this->uploadedBy = $uploadedBy;
        return $this;
    }

    public function getUploadedBy()
    {
        return $this->uploadedBy;
    }

    public function setUploadedTime($uploadedTime)
    {
        $this->uploadedTime = $uploadedTime;
        return $this;
    }

    public function getUploadedTime()
    {
        return $this->uploadedTime;
    }



}