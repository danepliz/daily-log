<?php

namespace location\models;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="CountryRepository")
 * @ORM\Table(name="ys_countries")
 */
class Country{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255, unique=True)
	 */
	private $name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nationality;

	/**
	 * @ORM\Column(type="string", length=10)
	 */
	private $iso_2;

	/**
	 * @ORM\Column(type="string", length=10)
	 */
	private $iso_3;

	/**
	 * @ORM\Column(type="string", length=10, nullable=true)
	 */
	private $dialing_code;

	public function id() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = ucwords($name);
	}

    public function getNationality()
    {
        return $this->nationality;
    }

    public function setNationality($nationality)
    {
        $this->nationality = strtoupper($nationality);
    }

	public function getIso_2() {
		return $this->iso_2;
	}

	public function setIso_2($iso_2) {
		$this->iso_2 = strtoupper($iso_2);
	}

	public function getIso_3() {
		return $this->iso_3;
	}

	public function setIso_3($iso_3) {
		$this->iso_3 = strtoupper($iso_3);
	}

	public function getDialingCode() {
		return $this->dialing_code;
	}

	public function setDialingCode($code) {
		$this->dialing_code = $code;
	}

	public function __toString() {
		return $this->name;
	}
}
