<?php

namespace transport\models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Transport
 * @ORM\Entity(repositoryClass="TransportRepository")
 * @ORM\Table(name="ys_transports")
 */
class Transport{

    const TRANSPORT_STATUS_ACTIVE = 'ACTIVE';
    const TRANSPORT_STATUS_SUSPENDED = 'SUSPENDED';
    const TRANSPORT_STATUS_CLOSED = 'CLOSED';

//    const HOTEL_BOOKING_TYPE_ROOM_BASIS = 'ROOM_BASIS';
//    const HOTEL_BOOKING_TYPE_PACKAGE_BASIS = 'PACKAGE_BASIS';
//    const HOTEL_BOOKING_TYPE_SERVICE_BASIS = 'SERVICE_BASIS';

//    const HOTEL_RATE_VARIATION_STRATEGY_SEASONAL = 100;
//    const HOTEL_RATE_VARIATION_STRATEGY_NONE = 101;

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
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255)
     */
    private  $slug;

//    /**
//     * @ORM\ManyToOne(targetEntity="location\models\Country")
//     */
//    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="text")
     */
    private $address;

    /**
     * @ORM\Column(type="array")
     */
    private $phones;

//    /**
//     * @ORM\Column(type="string", length=50)
//     */
//    private $fax;

//    /**
//     * @ORM\Column(type="array")
//     */
//    private $emails;

//    /**
//     * @ORM\Column(type="string", length=255, nullable=true)
//     */
//    private $website1;
//
//    /**
//     * @ORM\Column(type="string", length=255, nullable=true)
//     */
//    private $website2;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status = self::TRANSPORT_STATUS_ACTIVE;

//    /**
//     * @ORM\Column(type="text")
//     */
//    private $others;
//
//    /**
//     * @ORM\OneToMany(targetEntity="HotelContactPerson", mappedBy="hotel")
//     */
//    private $contactPersons;

//    /**
//     * @ORM\ManyToOne(targetEntity="HotelCategory")
//     */
//    private  $category;

//    /**
//     * @ORM\ManyToOne(targetEntity="HotelGrade")
//     */
//    private  $grade;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $updated;

//    /**
//     * @ORM\ManyToMany(targetEntity="HotelRoomCategory",cascade={"persist"})
//     * @ORM\JoinTable(name="ys_hotel_room_category_relations")
//     */
//    private $room_categories;
//
//    /**
//     * @ORM\ManyToMany(targetEntity="HotelRoomType",cascade={"persist"})
//     * @ORM\JoinTable(name="ys_hotel_room_type_relations")
//     */
//    private $room_types;
//
//    /**
//     * @ORM\ManyToMany(targetEntity="HotelRoomPlan",cascade={"persist"})
//     * @ORM\JoinTable(name="ys_hotel_room_plans_relations")
//     */
//    private $room_plans;
//
//    /**
//     * @ORM\OneToMany(targetEntity="HotelServices", mappedBy="hotel")
//     */
//    private $services;
//
//    /**
//     * @ORM\Column(type="string", length=20, nullable=true)
//     */
//    private $bookingType;
//
//    /**
//     * @ORM\Column(type="boolean")
//     */
//    private $hasBookingTypeRoomBasis;
//
//    /**
//     * @ORM\Column(type="boolean")
//     */
//    private $hasBookingTypePackageBasis;
//
//    /**
//     * @ORM\OneToMany(targetEntity="HotelPackage", mappedBy="hotel")
//     */
//    private $packages;

    /**
     * @ORM\ManyToMany(targetEntity="currency\models\Currency",cascade={"persist"})
     * @ORM\JoinTable(name="ys_transport_payable_currency_relations")
     */
    private $payable_currencies;

//    /**
//     * @ORM\Column(type="string", length=50)
//     */
//    private $payment_strategy = self::PAYMENT_STRATEGY_NONE;
//

//    /**
//     * @ORM\Column(type="integer", length=20, nullable=true)
//     */
//    private $rateVariationStrategy = self::HOTEL_RATE_VARIATION_STRATEGY_NONE;
//
//    /**
//     * @ORM\Column(type="decimal", scale=3, precision=10)
//     */
//    private $payment_strategy_percent;

//    /**
//     * @ORM\OneToMany(targetEntity="Rate", mappedBy="hotel")
//     */
//    private $rates;
//
//    /**
//     * @ORM\OneToMany(targetEntity="HotelSeason", mappedBy="hotel")
//     */
//    private $seasons;
//
//    /**
//     * @ORM\OneToMany(targetEntity="HotelOutlet", mappedBy="hotel")
//     */
//    private $outlets;

    public static $transport_status = array(
        self::TRANSPORT_STATUS_ACTIVE       => 'ACTIVE',
        self::TRANSPORT_STATUS_SUSPENDED    => 'SUSPENDED',
        self::TRANSPORT_STATUS_CLOSED       => 'CLOSED',
    );

    const PAYMENT_STRATEGY_VAT_ONLY = 'VAT_ONLY';
    const PAYMENT_STRATEGY_SC_ONLY = 'SC_ONLY';
    const PAYMENT_STRATEGY_VAT_SC = 'VAT_SC';
    const PAYMENT_STRATEGY_NONE = 'NONE';

    public static $paymentStrategies = [
        self::PAYMENT_STRATEGY_NONE => 'NONE',
        self::PAYMENT_STRATEGY_VAT_ONLY => 'VAT Only',
        self::PAYMENT_STRATEGY_SC_ONLY => 'SC Only',
        self::PAYMENT_STRATEGY_VAT_SC => 'VAT + SC',
    ];

//    public static $bookingTypes = [
//        self::HOTEL_BOOKING_TYPE_ROOM_BASIS => 'Room Basis',
//        self::HOTEL_BOOKING_TYPE_PACKAGE_BASIS => 'Package Basis'
//    ];

/*    public static $rateVariationStrategies = [
        self::HOTEL_RATE_VARIATION_STRATEGY_SEASONAL => 'Seasonal',
        self::HOTEL_RATE_VARIATION_STRATEGY_NONE => 'None'
    ];*/


    public function __construct(){
//        $this->contactPersons = new ArrayCollection();
//        $this->room_categories = new ArrayCollection();
//        $this->room_plans = new ArrayCollection();
//        $this->room_types = new ArrayCollection();
//        $this->services=new ArrayCollection();
        $this->payable_currencies = new ArrayCollection();
//        $this->markets = new ArrayCollection();
//        $this->packages = new ArrayCollection();
//        $this->rates = new ArrayCollection();
//        $this->seasons = new ArrayCollection();
//        $this->outlets = new ArrayCollection();
    }

    public function id(){
        return $this->id;
    }

    public function slug(){
        return $this->slug;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getName(){
        return $this->name;
    }

//    public function getCountry(){
//        return $this->country;
//    }
//
//    public function setCountry($country){
//        $this->country = $country;
//    }

    public function setCity($city){
        $this->city = $city;
    }

    public function getCity(){
        return $this->city;
    }

    public function setAddress($address){
        $this->address = ucwords($address);
    }

    public function getAddress(){
        return $this->address;
    }

    public function setPhones($phone){
        $this->phones = $phone;
    }

    public function getPhones(){
        return $this->phones;
    }

//    public function setFax($fax){
//        $this->fax = $fax;
//    }
//
//    public function getFax(){
//        return $this->fax;
//    }
//
//    public function setEmails($emails){
//        $this->emails = $emails;
//    }
//
//    public function getEmails(){
//        return $this->emails;
//    }
//
//    public function getWebsite1(){
//        return $this->website1;
//    }
//
//    public function setWebsite1($site){
//        $this->website1 = $site;
//    }
//
//    public function getWebsite2(){
//        return $this->website2;
//    }
//
//    public function setWebsite2($site){
//        $this->website2 = $site;
//    }
//
//    public function setOthers($others){
//        $this->others = $others;
//    }
//
//    public function getOthers(){
//        return $this->others;
//    }
//
//    public function getHotelContactPersons(){
//        return $this->contactPersons;
//    }
//
//    public function addHotelContactPerson($person){
//        $this->contactPersons[] = $person;
//    }
//
//    public function removeHotelContactPerson($person){
//        $this->contactPersons->removeElement($person);
//    }
//
//    public function removeAllHotelContactPerson(){
//        $this->contactPersons = new ArrayCollection();
//    }

    public function created(){
        return $this->created;
    }

    public function updated(){
        return $this->updated;
    }

//    public  function getCategory(){
//        return $this->category;
//    }
//
//    public function setCategory($cat){
//        $this->category = $cat;
//    }
//
//    public  function getGrade(){
//        return $this->grade;
//    }
//
//    public function setGrade($grade){
//        $this->grade = $grade;
//    }

    public function getStatus(){
        return $this->status;
    }

    public function isActive(){
        return $this->status == self::TRANSPORT_STATUS_ACTIVE;
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function activate(){
        $this->status = self::TRANSPORT_STATUS_ACTIVE;
    }

//    public function getRoomCategories()
//    {
//        return $this->room_categories;
//    }
//
//    public function addRoomCategory(HotelRoomCategory $category){
//        $this->room_categories[] = $category;
//    }
//
//    public function removeRoomCategory(HotelRoomCategory $category){
//        $this->room_categories->removeElement($category);
//    }
//
//    public function resetRoomCategories(){
//        $this->room_categories = new ArrayCollection();
//    }
//
//    public function getRoomPlans(){
//        return $this->room_plans;
//    }
//
//    public function addRoomPlan(HotelRoomPlan $plan){
//        $this->room_plans[] = $plan;
//    }
//
//    public function removeRoomPlan(HotelRoomPlan $plan){
//        $this->room_plans->removeElement($plan);
//    }
//
//    public function resetRoomPlans(){
//        $this->room_plans = new ArrayCollection();
//    }
//
//    public function getRoomTypes(){
//        return $this->room_types;
//    }
//
//    public function addRoomType(HotelRoomType $type){
//        $this->room_types[] = $type;
//    }
//
//    public function removeRoomType(HotelRoomType $type){
//        $this->room_types->removeElement($type);
//    }
//
//    public function resetRoomTypes(){
//        $this->room_types = new ArrayCollection();
//    }
//
//    public function getServices(){
//        return $this->services;
//    }
//
//    public function addService($service){
//        $this->services[] = $service;
//    }
//
//    public function removeService($service){
//        $this->services->removeElement($service);
//    }
//
//    public function resetServices(){
//        $this->services = new ArrayCollection();
//    }

    public function getPayableCurrencies(){
        return $this->payable_currencies;
    }

    public function addPayableCurrency($currency){
        $this->payable_currencies[] = $currency;
    }

    public function removePayableCurrency($currency){
        $this->payable_currencies->removeElement($currency);
    }

    public function resetPayableCurrencies(){
        $this->payable_currencies = new ArrayCollection();
    }

//    public function getPaymentStrategy()
//    {
//        return $this->payment_strategy;
//    }
//
//    public function setPaymentStrategy($payment_strategy)
//    {
//        $this->payment_strategy = $payment_strategy;
//    }
//
//    public function getPaymentStrategyPercent()
//    {
//        return $this->payment_strategy_percent;
//    }
//
//    public function setPaymentStrategyPercent($payment_strategy_percent)
//    {
//        $this->payment_strategy_percent = $payment_strategy_percent;
//    }
//
//    public function getPackages()
//    {
//        return $this->packages;
//    }
//
//    public function addPackage($package)
//    {
//        $this->packages[] = $package;
//    }
//
//    public function removePackage($package){
//
//        $this->packages->removeElement($package);
//
//    }
//    public function resetPackages(){
//
//        $this->packages = new ArrayCollection();
//    }
//
//    public function getBookingType(){
//        return $this->bookingType;
//    }
//
//    public function setBookingType($type){
//        $this->bookingType = $type;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getRateVariationStrategy()
//    {
//        return $this->rateVariationStrategy;
//    }
//
//    /**
//     * @param mixed $rateVariationStrategy
//     */
//    public function setRateVariationStrategy($rateVariationStrategy)
//    {
//        $this->rateVariationStrategy = $rateVariationStrategy;
//    }

    public function getRates(){
        return $this->rates;
    }

    public function addRate($rate){
        $this->rates[] = $rate;
    }

    public function removeRate($rate){
        $this->rates->removeElement($rate);
    }

    public function resetRates(){
        $this->rates = new ArrayCollection();
    }

//    public function getSeasons(){
//        return $this->seasons;
//    }
//
//    public function addSeason($season){
//        $this->seasons[] = $season;
//    }
//
//    public function removeSeason($season){
//        $this->seasons->removeElement($season);
//    }
//
//    public function resetSeasons(){
//        $this->seasons = new ArrayCollection();
//    }
//
//    public function hasBookingTypePackageBasis()
//    {
//        return $this->hasBookingTypePackageBasis;
//    }
//
//    public function setHasBookingTypePackageBasis($hasBookingTypePackageBasis)
//    {
//        $this->hasBookingTypePackageBasis = $hasBookingTypePackageBasis;
//    }
//
//    public function hasBookingTypeRoomBasis()
//    {
//        return $this->hasBookingTypeRoomBasis;
//    }
//
//    public function setHasBookingTypeRoomBasis($hasBookingTypeRoomBasis)
//    {
//        $this->hasBookingTypeRoomBasis = $hasBookingTypeRoomBasis;
//    }
//
//    public function getOutlets(){
//        return $this->outlets;
//    }
//
//    public function addOutlet($outlet){
//        $this->outlets[] = $outlet;
//    }
//
//    public function removeOutlet($outlet){
//        $this->outlets->removeElement($outlet);
//    }
//
//    public function resetOutlets(){
//        $this->outlets = new ArrayCollection();
//    }

}

