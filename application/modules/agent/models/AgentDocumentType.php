<?php

namespace agent\models;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="AgentDocumentRepository")
 * @ORM\Table(name="ys_agent_document_types")
 */
class AgentDocumentType {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var datetime $created
     *
     * @gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;


    public function __construct() {

    }

	public function id() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = ucwords($name);
	}

	public function getDescription(){
        return $this->description;
    }

    public function setDescription($desc){
        $this->description = $desc;
    }

    public function created(){
        return $this->created;
    }

    public function __toString() {
        return $this->name;
    }

}
