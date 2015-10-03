<?php
namespace task\models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

if( !defined('BASEPATH')) exit('No direct script access allowed');


/**
 * @ORM\Entity
 * @ORM\Table(name="ys_task_attachments")
 */
class TaskAttachment{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="caption", type="string", length=255, nullable=true)
     */
    private $caption;

    /**
     * @ORM\Column(name="filename", type="string", length=255, nullable=false)
     */
    private $filename;

    /**
     * @ORM\Column(name="extension", type="string", length=10, nullable=false)
     */
    private $extension;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="uploaded_date", type="datetime")
     */
    private $uploadedDate;

    /**
     * @ORM\ManyToOne(targetEntity="user\models\User")
     */
    private $uploadedBy;

    /**
     * @ORM\Column(name="is_deleted", type="boolean")
     */
    private $deleted = FALSE;

    /**
     * @ORM\ManyToOne(targetEntity="task\models\Task", inversedBy="attachments")
     */
    private $task;

    public function id()
    {
        return $this->id;
    }

    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getDeleted()
    {
        return $this->deleted;
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

    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    public function getFilename()
    {
        return $this->filename;
    }


    public function getId()
    {
        return $this->id;
    }

    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    public function getTask()
    {
        return $this->task;
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

    public function setUploadedDate($uploadedDate)
    {
        $this->uploadedDate = $uploadedDate;

        return $this;
    }

    public function getUploadedDate()
    {
        return $this->uploadedDate;
    }

    public  function isDeleted()
    {
        return $this->deleted;
    }

    public function markAsDeleted()
    {
        $this->deleted = TRUE;

        return $this;
    }

    public function markAsNotDeleted()
    {
        $this->deleted = FALSE;

        return $this;
    }



}

