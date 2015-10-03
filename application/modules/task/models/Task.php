<?php
namespace task\models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

if( !defined('BASEPATH')) exit('No direct script access allowed');


/**
 * @ORM\Entity
 * @ORM\Table(name="ys_tasks")
 */
class Task{

    const TASK_TYPE_OPEN = 11;
    const TASK_TYPE_CLOSE = 12;

    const TASK_STATUS_NEW = 100;
    const TASK_STATUS_OPEN = 102;
    const TASK_STATUS_CLOSED = 103;
    const TASK_STATUS_PROGRESSING = 104;
    const TASK_STATUS_FINISHED = 105;
    const TASK_STATUS_DELETED = 106;


    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    /**
     * @ORM\ManyToOne(targetEntity="user\models\User")
     */
    private $createdBy;

    /**
     * @ORM\Column(name="task_type", type="integer", length=4, nullable=false)
     */
    private $type = self::TASK_TYPE_CLOSE;

    /**
     * @ORM\Column(name="task_status", type="integer", length=4, nullable=false)
     */
    private $status = self::TASK_STATUS_NEW;

    /**
     * @ORM\Column(name="task_number", type="string", length=100, nullable=false)
     */
    private $taskNumber;

    /**
     * @ORM\ManyToOne(targetEntity="project\models\Project")
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="task\models\Task", inversedBy="subTasks")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="task\models\Task", mappedBy="parent")
     */
    private $subTasks;

    /**
     * @ORM\OneToMany(targetEntity="task\models\TaskAttachment", mappedBy="task")
     */
    private $attachments;

    /**
     * @ORM\OneToMany(targetEntity="task\models\TaskComment", mappedBy="task")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="user\models\User", cascade={"persist"})
     * @ORM\joinTable(name="ys_task_members")
     */
    private $members;

    public function __construct(){
        $this->attachments = new ArrayCollection();
        $this->subTasks = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->members = new ArrayCollection();
    }

    public function id(){
        return $this->id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getCreatedDate()
    {
        return $this->createdDate;
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

    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent()
    {
        return $this->parent;
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

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setSubTasks($subTasks)
    {
        $this->subTasks = $subTasks;

        return $this;
    }

    public function getSubTasks()
    {
        return $this->subTasks;
    }

    public function setTaskNumber($taskNumber)
    {
        $this->taskNumber = $taskNumber;

        return $this;
    }

    public function getTaskNumber()
    {
        return $this->taskNumber;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
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

    public function addAttachment($attachment)
    {
        $this->attachments->add($attachment);

        return $this;
    }

    public function removeAttachment($attachment)
    {
        $this->attachments->removeElement($attachment);

        return $this;
    }

    public function resetAttachments()
    {
        $this->attachments = new ArrayCollection();

        return $this;
    }

    public function addComment($comment)
    {
        $this->comments->add($comment);

        return $this;
    }

    public function removeComment($comment)
    {
        $this->comments->removeElement($comment);

        return $this;
    }

    public function resetComment()
    {
        $this->comments = new ArrayCollection();

        return $this;
    }

    public function addSubTask($task)
    {
        $this->subTasks->add($task);

        return $this;
    }

    public function removeSubTask($task)
    {
        $this->subTasks->removeElement($task);

        return $this;
    }

    public function resetSubTasks()
    {
        $this->subTasks = new ArrayCollection();

        return $this;
    }

    public function setMembers($members)
    {
        $this->members = $members;

        return $this;
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function addMember($member)
    {
        $this->members->add($member);

        return $this;
    }

    public function removeMember($member)
    {
        $this->members->removeElement($member);

        return $this;
    }

    public function resetMembers()
    {
        $this->members = new ArrayCollection();

        return $this;
    }

}

