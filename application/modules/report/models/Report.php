<?php

namespace report\models;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo,
	Doctrine\Common\Collections\ArrayCollection; 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="ReportRepository")
 * @ORM\Table(name="ys_reports")
 */
class Report{
	
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
     * @ORM\Column(type="string", length=255, nullable=false, unique = true)
     */
    private $slug;
    
    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $description;
    
    /**
     * @ORM\Column(type="text")
     */
    private $sqlquery;

	/**
     * @ORM\Column(type="text", nullable=true)
     */
    private $usrgroups;
    
    /**
     * @ORM\ManyToOne(targetEntity="ReportGroup")
     */
    private $reportgroup;


	public function id()
	{
	    return $this->id;
	}

	public function getDescr()
	{
	    return $this->description;
	}

	public function setDescr($description)
	{
	    $this->description = $description;
	}
	
	public function getName()
	{
	    return $this->name;
	}

	public function setName($name)
	{
	    $this->name = $name;
	}

	public function getSqlQuery()
	{
	    return $this->sqlquery;
	}

	public function setSqlQuery($sqlquery)
	{
	    $this->sqlquery = $sqlquery;
	}
	
	public function getUserGroups()
	{
	    return unserialize($this->usrgroups);
	}

	public function setUserGroups($usrgroups)
	{
	    $this->usrgroups = serialize($usrgroups);
	}


	public function getGroup()
	{
	    return $this->reportgroup;
	}

	public function setGroup($reportgroup)
	{
	    $this->reportgroup = $reportgroup;
	}
	
	public function getSlug(){
		return $this->slug;
	}

}