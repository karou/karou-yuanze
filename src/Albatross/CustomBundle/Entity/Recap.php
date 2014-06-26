<?php

namespace Albatross\CustomBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recap
 */
class Recap
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Albatross\CustomBundle\Entity\SurveyNumber
     */
    private $actual;

    /**
     * @var \Albatross\CustomBundle\Entity\SurveyNumber
     */
    private $planned;

    /**
     * @var \Albatross\CustomBundle\Entity\Infomation
     */
    private $infomations;

    /**
     * @var \Albatross\AceBundle\Entity\Country
     */
    private $country;


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
     * Set actual
     *
     * @param \Albatross\CustomBundle\Entity\SurveyNumber $actual
     * @return Recap
     */
    public function setActual(\Albatross\CustomBundle\Entity\SurveyNumber $actual = null)
    {
        $this->actual = $actual;

        return $this;
    }

    /**
     * Get actual
     *
     * @return \Albatross\CustomBundle\Entity\SurveyNumber 
     */
    public function getActual()
    {
        return $this->actual;
    }

    /**
     * Set planned
     *
     * @param \Albatross\CustomBundle\Entity\SurveyNumber $planned
     * @return Recap
     */
    public function setPlanned(\Albatross\CustomBundle\Entity\SurveyNumber $planned = null)
    {
        $this->planned = $planned;

        return $this;
    }

    /**
     * Get planned
     *
     * @return \Albatross\CustomBundle\Entity\SurveyNumber 
     */
    public function getPlanned()
    {
        return $this->planned;
    }

    /**
     * Set infomations
     *
     * @param \Albatross\CustomBundle\Entity\Infomation $infomations
     * @return Recap
     */
    public function setInfomations(\Albatross\CustomBundle\Entity\Infomation $infomations = null)
    {
        $this->infomations = $infomations;

        return $this;
    }

    /**
     * Get infomations
     *
     * @return \Albatross\CustomBundle\Entity\Infomation 
     */
    public function getInfomations()
    {
        return $this->infomations;
    }

    /**
     * Set country
     *
     * @param \Albatross\AceBundle\Entity\Country $country
     * @return Recap
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
    
    public function __toString() {
        return $this->id;
    }
    /**
     * @var \Albatross\CustomBundle\Entity\Customwave
     */
    private $customwave;


    /**
     * Set customwave
     *
     * @param \Albatross\CustomBundle\Entity\Customwave $customwave
     * @return Recap
     */
    public function setCustomwave(\Albatross\CustomBundle\Entity\Customwave $customwave = null)
    {
        $this->customwave = $customwave;

        return $this;
    }

    /**
     * Get customwave
     *
     * @return \Albatross\CustomBundle\Entity\Customwave 
     */
    public function getCustomwave()
    {
        return $this->customwave;
    }
    /**
     * @var string
     */
    private $name;


    /**
     * Set name
     *
     * @param string $name
     * @return Recap
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->country = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add country
     *
     * @param \Albatross\AceBundle\Entity\Country $country
     * @return Recap
     */
    public function addCountry(\Albatross\AceBundle\Entity\Country $country)
    {
        $this->country[] = $country;

        return $this;
    }

    /**
     * Remove country
     *
     * @param \Albatross\AceBundle\Entity\Country $country
     */
    public function removeCountry(\Albatross\AceBundle\Entity\Country $country)
    {
        $this->country->removeElement($country);
    }
    /**
     * @var boolean
     */
    private $countryType;


    /**
     * Set countryType
     *
     * @param boolean $countryType
     * @return Recap
     */
    public function setCountryType($countryType)
    {
        $this->countryType = $countryType;

        return $this;
    }

    /**
     * Get countryType
     *
     * @return boolean 
     */
    public function getCountryType()
    {
        return $this->countryType;
    }

    /**
     * @var string
     */
    private $submittime;


    /**
     * Set submittime
     *
     * @param string $submittime
     * @return Recap
     */
    public function setSubmittime($submittime)
    {
        $this->submittime = $submittime;

        return $this;
    }

    /**
     * Get submittime
     *
     * @return string 
     */
    public function getSubmittime()
    {
        return $this->submittime;
    }
    /**
     * @var \Albatross\UserBundle\Entity\User
     */
    private $user;


    /**
     * Set user
     *
     * @param \Albatross\UserBundle\Entity\User $user
     * @return Recap
     */
    public function setUser(\Albatross\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Albatross\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $aolquestionnaire;


    /**
     * Add aolquestionnaire
     *
     * @param \Albatross\CustomBundle\Entity\Aolquestionnaire $aolquestionnaire
     * @return Recap
     */
    public function addAolquestionnaire(\Albatross\CustomBundle\Entity\Aolquestionnaire $aolquestionnaire)
    {
        $this->aolquestionnaire[] = $aolquestionnaire;

        return $this;
    }

    /**
     * Remove aolquestionnaire
     *
     * @param \Albatross\CustomBundle\Entity\Aolquestionnaire $aolquestionnaire
     */
    public function removeAolquestionnaire(\Albatross\CustomBundle\Entity\Aolquestionnaire $aolquestionnaire)
    {
        $this->aolquestionnaire->removeElement($aolquestionnaire);
    }

    /**
     * Get aolquestionnaire
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAolquestionnaire()
    {
        return $this->aolquestionnaire;
    }
}
