<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Location
 */
class Location
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $LocStoreID;

    /**
     * @var string
     */
    private $LocName;

    /**
     * @var string
     */
    private $LocCountryCode;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $aolsurvey;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->aolsurvey = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set LocStoreID
     *
     * @param string $locStoreID
     * @return Location
     */
    public function setLocStoreID($locStoreID)
    {
        $this->LocStoreID = $locStoreID;

        return $this;
    }

    /**
     * Get LocStoreID
     *
     * @return string 
     */
    public function getLocStoreID()
    {
        return $this->LocStoreID;
    }

    /**
     * Set LocName
     *
     * @param string $locName
     * @return Location
     */
    public function setLocName($locName)
    {
        $this->LocName = $locName;

        return $this;
    }

    /**
     * Get LocName
     *
     * @return string 
     */
    public function getLocName()
    {
        return $this->LocName;
    }

    /**
     * Set LocCountryCode
     *
     * @param string $locCountryCode
     * @return Location
     */
    public function setLocCountryCode($locCountryCode)
    {
        $this->LocCountryCode = $locCountryCode;

        return $this;
    }

    /**
     * Get LocCountryCode
     *
     * @return string 
     */
    public function getLocCountryCode()
    {
        return $this->LocCountryCode;
    }

    /**
     * Add aolsurvey
     *
     * @param \Albatross\AceBundle\Entity\Aolsurvey $aolsurvey
     * @return Location
     */
    public function addAolsurvey(\Albatross\AceBundle\Entity\Aolsurvey $aolsurvey)
    {
        $this->aolsurvey[] = $aolsurvey;

        return $this;
    }

    /**
     * Remove aolsurvey
     *
     * @param \Albatross\AceBundle\Entity\Aolsurvey $aolsurvey
     */
    public function removeAolsurvey(\Albatross\AceBundle\Entity\Aolsurvey $aolsurvey)
    {
        $this->aolsurvey->removeElement($aolsurvey);
    }

    /**
     * Get aolsurvey
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAolsurvey()
    {
        return $this->aolsurvey;
    }
    /**
     * @var \Albatross\AceBundle\Entity\Country
     */
    private $country;


    /**
     * Set country
     *
     * @param \Albatross\AceBundle\Entity\Country $country
     * @return Location
     */
    public function setCountry(\Albatross\AceBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Albatross\AceBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }
}
