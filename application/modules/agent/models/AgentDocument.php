<?php

namespace agent\models;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="AgentDocumentRepository")
 * @ORM\Table(name="ys_agents_documents")
 */
class AgentDocument {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="AgentDocumentType")
	 */
	private $agent_document_type;

    /**
     * @ORM\ManyToOne(targetEntity="Agent")
     */
    private $agent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $document_number;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $issued_address;

    /**
     * @var \DateTime $issuedDate
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $issued_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $issued_by;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_renewable = FALSE;

    /**
     * @var \DateTime $expiryDate
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expiry_date;

    /**
     * @var \DateTime $expiryDate
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_renewed_date;

    /**
     * @var \DateTime $expiryDate
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $renew_date;

    /**
     * @ORM\Column(type="text")
     */
    private $remarks;

    /**
     * @var datetime $created
     *
     * @gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;


    public function __construct() {

    }

    public function __toString() {
        return $this->document_number;
    }

	public function id() {
		return $this->id;
	}

    public function created(){
        return $this->created;
    }

    public function getDocumentNumber()
    {
        return $this->document_number;
    }

    public function setDocumentNumber($document_number)
    {
        $this->document_number = $document_number;
    }

    public function getExpiryDate()
    {
        return $this->expiry_date;
    }

    public function setExpiryDate($expiry_date)
    {
        $this->expiry_date = $expiry_date;
    }

    public function getFileName()
    {
        return $this->file_name;
    }

    public function setFileName($file_name)
    {
        $this->file_name = $file_name;
    }

    public function isRenewable()
    {
        return $this->is_renewable;
    }

    public function setAsRenewable()
    {
        $this->is_renewable = TRUE;
    }

    public function setAsNonRenewable()
    {
        $this->is_renewable = FALSE;
    }

    public function getIssuedAddress()
    {
        return $this->issued_address;
    }

    public function setIssuedAddress($issued_address)
    {
        $this->issued_address = $issued_address;
    }

    public function getIssuedBy()
    {
        return $this->issued_by;
    }

    public function setIssuedBy($issued_by)
    {
        $this->issued_by = $issued_by;
    }

    public function getIssuedDate()
    {
        return $this->issued_date;
    }

    public function setIssuedDate($issued_date)
    {
        $this->issued_date = $issued_date;
    }

    public function getLastRenewedDate()
    {
        return $this->last_renewed_date;
    }

    public function setLastRenewedDate($last_renewed_date)
    {
        $this->last_renewed_date = $last_renewed_date;
    }

    public function getRemarks()
    {
        return $this->remarks;
    }

    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
    }

    public function getRenewDate()
    {
        return $this->renew_date;
    }

    public function setRenewDate($renew_date)
    {
        $this->renew_date = $renew_date;
    }

    public function getAgent()
    {
        return $this->agent;
    }

    public function setAgent(Agent $agent)
    {
        $this->agent = $agent;
    }

    public function getAgentDocumentType()
    {
        return $this->agent_document_type;
    }

    public function setAgentDocumentType(AgentDocumentType $agent_document_type)
    {
        $this->agent_document_type = $agent_document_type;
    }


}
