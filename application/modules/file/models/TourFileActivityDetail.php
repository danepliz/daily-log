<?php

namespace file\models;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="file\models\TourFileRepository")
 * @ORM\Table(name="ys_tour_file_activity_details")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"hotel" = "file\models\TourFileActivityDetailHotel"})
 */
class TourFileActivityDetail{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=4 )
     */
    private $tourType;

    /**
     * @ORM\ManyToOne(targetEntity="user\models\User")
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="user\models\User")
     */
    private $updatedBy;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\ManyToOne(targetEntity="TourFileActivity")
     */
    private $tourActivity;

    /**
     * @ORM\ManyToOne(targetEntity="TourFileActivityDetail")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="TourFileActivityDetail", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isComplimentary;

    /**
     * @ORM\Column(type="decimal", scale=3, precision=10, nullable=true)
     */
    private $actualRate;

    /**
     * @ORM\Column(type="decimal", scale=3, precision=10)
     */
    private $totalAmount;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSpecialRateApplied = FALSE;

    /**
     * @ORM\Column(type="decimal", scale=3, precision=10, nullable=true)
     */
    private $specialRate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $reasonForSpecialRate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isXoGenerated = FALSE;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = FALSE;

    /**
     * @ORM\ManyToOne(targetEntity="currency\models\Currency")
     */
    private $currency;

    /**
     * @ORM\Column(type="decimal", scale=3, precision=10, nullable=true)
     */
    private $margin;

    /**
     * @ORM\Column(type="decimal", scale=3, precision=10, nullable=true)
     */
    private $payableAmount;

    /**
     * @ORM\Column(type="decimal", scale=3, precision=10, nullable=true)
     */
    private $billingAmount;

    /**
     * @ORM\Column(type="decimal", scale=3, precision=5, nullable=true)
     */
    private $paymentStrategyPercent;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $paymentStrategyType;



    public function __construct(){
        $this->children = new ArrayCollection();
    }


    public function setId($id){
        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }

    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
    }

    public function isSpecialRateApplied(){
        return $this->isSpecialRateApplied;
    }

    public function markAsSpecialRateApplied(){
        $this->isSpecialRateApplied = TRUE;
    }

    public function markAsSpecialRateNotApplied(){
        $this->isSpecialRateApplied = FALSE;
    }

    public function isComplimentary()
    {
        return $this->isComplimentary;
    }

    public function markAsComplimentary()
    {
        $this->isComplimentary = TRUE;
    }

    public function unMarkAsComplimentary(){
        $this->isComplimentary = FALSE;
    }

    public function getActualRate()
    {
        return $this->actualRate;
    }

    public function setActualRate($actualRate)
    {
        $this->actualRate = $actualRate;
    }

    public function getReasonForSpecialRate()
    {
        return $this->reasonForSpecialRate;
    }

    public function setReasonForSpecialRate($reasonForSpecialRate)
    {
        $this->reasonForSpecialRate = $reasonForSpecialRate;
    }

    public function getSpecialRate()
    {
        return $this->specialRate;
    }

    public function setSpecialRate($specialRate)
    {
        $this->specialRate = $specialRate;
    }


    public function created()
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
    }

    public function getTourType()
    {
        return $this->tourType;
    }

    public function setTourType($tourType)
    {
        $this->tourType = $tourType;
    }

    public function updated()
    {
        return $this->updated;
    }

    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    public function getTourActivity()
    {
        return $this->tourActivity;
    }

    public function setTourActivity($tourActivity)
    {
        $this->tourActivity = $tourActivity;
    }

    public function isXoGenerated(){
        return $this->isXoGenerated;
    }

    public function markAsXoGenerated(){
        $this->isXoGenerated = TRUE;
    }

    public function markAsXoNotGenerated(){
        $this->isXoGenerated = FALSE;
    }

    public function markAsDeleted()
    {
        $this->deleted = TRUE;
    }

    public function isDeleted()
    {
        return $this->deleted;
    }

    public function getMargin()
    {
        return $this->margin;
    }

    public function setMargin($margin)
    {
        $this->margin = $margin;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getPayableAmount()
    {
        return $this->payableAmount;
    }

    public function setPayableAmount($payableAmount)
    {
        $this->payableAmount = $payableAmount;
    }

    public function getPaymentStrategyPercent()
    {
        return $this->paymentStrategyPercent;
    }

    public function setPaymentStrategyPercent($paymentStrategyPercent)
    {
        $this->paymentStrategyPercent = $paymentStrategyPercent;
    }

    public function getPaymentStrategyType()
    {
        return $this->paymentStrategyType;
    }

    public function setPaymentStrategyType($paymentStrategyType)
    {
        $this->paymentStrategyType = $paymentStrategyType;
    }

    public function getBillingAmount()
    {
        return $this->billingAmount;
    }

    public function setBillingAmount($billingAmount)
    {
        $this->billingAmount = $billingAmount;
    }

    public function getParent(){
        return $this->parent;
    }

    public function setParent($parent){
        $this->parent = $parent;
    }

    public function getChildren(){
        return $this->children;
    }

    public function addChildren($child){
        $this->children[] = $child;
    }




}