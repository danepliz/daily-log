<?php

namespace project\models;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use	Doctrine\ORM\Mapping as ORM;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="ProjectRepository")
 * @ORM\Table(name="ys_project_meta")
 */
class ProjectMeta
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
     */
    private $id;
    
	 /**
     * @ORM\ManyToOne(targetEntity="project\models\Project", inversedBy="meta")
     */
    private $project;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $meta_key;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_value;

    /**
     * @ORM\Column(type="boolean")
     */
    private $show_to_all = FALSE;


    public function id()
	{
	    return $this->id;
	}

    public function getMetaKey()
    {
        return $this->meta_key;
    }

    public function setMetaKey($meta_key)
    {
        $this->meta_key = $meta_key;
        return $this;
    }

    public function getMetaValue()
    {
        return $this->meta_value;
    }

    public function setMetaValue($meta_value)
    {
        $this->meta_value = $meta_value;
        return $this;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }

    public function showToAll(){
        return $this->show_to_all;
    }

    public function markAsShowToAll(){
        $this->show_to_all = TRUE;
        return $this;
    }

    public function markAsDoNotShowToAll(){
        $this->show_to_all = FALSE;
        return $this;
    }


}