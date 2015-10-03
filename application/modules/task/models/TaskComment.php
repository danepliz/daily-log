<?php
namespace task\models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

if( !defined('BASEPATH')) exit('No direct script access allowed');


/**
 * @ORM\Entity
 * @ORM\Table(name="ys_task_comments")
 */
class TaskComment{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="comment", type="text", nullable=false)
     */
    private $comment;

    /**
     * @ORM\Column(name="comment_number", type="string", length=255, nullable=false)
     */
    private $commentNumber;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="commented_date", type="datetime")
     */
    private $commentedDate;

    /**
     * @ORM\ManyToOne(targetEntity="user\models\User")
     */
    private $commentedBy;

    /**
     * @ORM\Column(name="is_deleted", type="boolean")
     */
    private $deleted = FALSE;

    /**
     * @ORM\ManyToOne(targetEntity="task\models\Task")
     */
    private $task;

    /**
     * @ORM\ManyToOne(targetEntity="task\models\TaskComment", inversedBy="replies")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="task\models\TaskComment", mappedBy="parent")
     */
    private $replies;

    /**
     * @ORM\OneToMany(targetEntity="task\models\TaskAttachment", mappedBy="comment")
     */
    private $attachments;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
        $this->replies = new ArrayCollection();
    }

    public function id()
    {
        return $this->id;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    public function getComment()
    {
        return $this->comment;
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

    public function setCommentedBy($commentedBy)
    {
        $this->commentedBy = $commentedBy;

        return $this;
    }

    public function getCommentedBy()
    {
        return $this->commentedBy;
    }

    public function setCommentedDate($commentedDate)
    {
        $this->commentedDate = $commentedDate;

        return $this;
    }

    public function getCommentedDate()
    {
        return $this->commentedDate;
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

    public function addReply($reply)
    {
        $this->replies->add($reply);

        return $this;
    }

    public function removeReply($reply)
    {
        $this->replies->removeElement($reply);

        return $this;
    }

    public function resetReplies()
    {
        $this->replies = new ArrayCollection();

        return $this;
    }


}

