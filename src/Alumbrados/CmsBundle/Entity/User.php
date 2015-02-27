<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Alumbrados\CmsBundle\Entity;

/**
 * Description of Customer
 *
 * @author Martijn
 */
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cms_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     *
     * @var string
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */     
    private $first_name;
    
    /**
     *
     * @var string
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */    
    private $last_name;
    
    /**
     *
     * @var string
     * @ORM\Column(name="initials", type="string", length=255, nullable=true)
     */    
    private $initials;
    
    /**
     *
     * @var string
     * @ORM\Column(name="street", type="string", length=255, nullable=true)
     */    
    private $street;
    
    /**
     *
     * @var integer
     * @ORM\Column(name="street_number", type="integer", length=11, nullable=true)
     */    
    private $street_number;
    
    /**
     *
     * @var string
     * @ORM\Column(name="street_number_addition", type="string", length=255, nullable=true)
     */    
    private $street_number_addition;
    
    /**
     *
     * @var string
     * @ORM\Column(name="zipcode", type="string", length=255, nullable=true)
     */    
    private $zipcode;
    
    /**
     *
     * @var string
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */    
    private $city;
    
    /**
     *
     * @var string
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */    
    private $country;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set first_name
     *
     * @param string $firstName
     * @return Customer
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get first_name
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     * @return Customer
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get last_name
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set initials
     *
     * @param string $initials
     * @return Customer
     */
    public function setInitials($initials)
    {
        $this->initials = $initials;

        return $this;
    }

    /**
     * Get initials
     *
     * @return string 
     */
    public function getInitials()
    {
        return $this->initials;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Customer
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set street_number
     *
     * @param integer $streetNumber
     * @return Customer
     */
    public function setStreetNumber($streetNumber)
    {
        $this->street_number = $streetNumber;

        return $this;
    }

    /**
     * Get street_number
     *
     * @return integer 
     */
    public function getStreetNumber()
    {
        return $this->street_number;
    }

    /**
     * Set street_number_addition
     *
     * @param string $streetNumberAddition
     * @return Customer
     */
    public function setStreetNumberAddition($streetNumberAddition)
    {
        $this->street_number_addition = $streetNumberAddition;

        return $this;
    }

    /**
     * Get street_number_addition
     *
     * @return string 
     */
    public function getStreetNumberAddition()
    {
        return $this->street_number_addition;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     * @return Customer
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string 
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Customer
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Customer
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }
}
