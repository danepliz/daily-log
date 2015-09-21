<?php

namespace project\models;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use	Doctrine\ORM\Mapping as ORM;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="ProjectRepository")
 * @ORM\Table(name="ys_comments")
 */
class Comment
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    
	/**
	* @ORM\Column(type="boolean")
	*/
	private $status = TRUE;
	
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
     * @ORM\ManyToOne(targetEntity="project\models\Comment", inversedBy="child")
     */
    private $parent;


    /**
     * @ORM\OneToMany(targetEntity="project\models\Comment", mappedBy="parent")
     */
    private $child;


    public function __construct(){
        $this->child = new ArrayCollection();
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

    public function getStatus()
    {
        return $this->status;
    }

    public function markAsActive()
    {
        $this->status = TRUE;
        return $this;
    }

    public function markAsInActive()
    {
        $this->status = FALSE;
        return $this;
    }

    public function getUpdated()
    {
        return $this->updated;
    }


    public function getParent(){
        return $this->parent;
    }

    public function setParent($parent){
        $this->parent = $parent;
        return $this;
    }

    public function getChild(){
        return $this->child;
    }

    public function addChild($child){
        $this->child->add($child);
        return $this;
    }

    public function resetChild(){
        $this->child = new ArrayCollection();
        return $this;
    }

    public function removeChild($child){
        $this->child->removeElement($child);
        return $this;
    }


}