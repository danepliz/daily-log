<?php

namespace user\models;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use	Doctrine\Common\Collections\ArrayCollection;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity
 * @ORM\Table(name="ys_permissions")
 */
class Permission
{
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
     * @ORM\Column(type="string",length=255)
     */
    private $description;
    
    public function __construct(){
    
    }
    
    public function __toString(){
    	return $this->name;
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
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
}