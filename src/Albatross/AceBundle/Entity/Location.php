<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Location
 *
 * @ORM\Table(name="location")
 * @ORM\Entity
 */
class Location
{
    /**
     * @var string
     *
     * @ORM\Column(name="loc_store_id", type="string", length=255, nullable=false)
     */
    private $locStoreId;

    /**
     * @var string
     *
     * @ORM\Column(name="loc_name", type="string", length=255, nullable=true)
     */
    private $locName;

    /**
     * @var string
     *
     * @ORM\Column(name="loc_country_code", type="string", length=255, nullable=false)
     */
    private $locCountryCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    private $country;



    /**
     * Set locStoreId
     *
     * @param string $locStoreId
     * @return Location
     */
    public function setLocStoreId($locStoreId)
    {
        $this->locStoreId = $locStoreId;

        return $this;
    }

    /**
     * Get locStoreId
     *
     * @return string 
     */
    public function getLocStoreId()
    {
        return $this->locStoreId;
    }

    /**
     * Set locName
     *
     * @param string $locName
     * @return Location
     */
    public function setLocName($locName)
    {
        $this->locName = $locName;

        return $this;
    }

    /**
     * Get locName
     *
     * @return string 
     */
    public function getLocName()
    {
        return $this->locName;
    }

    /**
     * Set locCountryCode
     *
     * @param string $locCountryCode
     * @return Location
     */
    public function setLocCountryCode($locCountryCode)
    {
        $this->locCountryCode = $locCountryCode;

        return $this;
    }

    /**
     * Get locCountryCode
     *
     * @return string 
     */
    public function getLocCountryCode()
    {
        return $this->locCountryCode;
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
