<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recap
 *
 * @ORM\Table(name="recap")
 * @ORM\Entity
 */
class Recap
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="countryType", type="boolean", nullable=false)
     */
    private $countrytype;

    /**
     * @var string
     *
     * @ORM\Column(name="submittime", type="string", length=255, nullable=false)
     */
    private $submittime;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Albatross\AceBundle\Entity\SurveyNumber
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\SurveyNumber")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actual_id", referencedColumnName="id")
     * })
     */
    private $actual;

    /**
     * @var \Albatross\AceBundle\Entity\Infomation
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Infomation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="infomations_id", referencedColumnName="id")
     * })
     */
    private $infomations;

    /**
     * @var \Albatross\AceBundle\Entity\Customwave
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Customwave")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customwave_id", referencedColumnName="id")
     * })
     */
    private $customwave;

    /**
     * @var \Albatross\AceBundle\Entity\SurveyNumber
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\SurveyNumber")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="planned_id", referencedColumnName="id")
     * })
     */
    private $planned;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Country", inversedBy="recap")
     * @ORM\JoinTable(name="recap_country",
     *   joinColumns={
     *     @ORM\JoinColumn(name="recap_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     *   }
     * )
     */
    private $country;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Aolquestionnaire", mappedBy="recap")
     */
    private $aolquestionnaire;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->country = new \Doctrine\Common\Collections\ArrayCollection();
        $this->aolquestionnaire = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set countrytype
     *
     * @param boolean $countrytype
     * @return Recap
     */
    public function setCountrytype($countrytype)
    {
        $this->countrytype = $countrytype;

        return $this;
    }

    /**
     * Get countrytype
     *
     * @return boolean 
     */
    public function getCountrytype()
    {
        return $this->countrytype;
    }

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \Albatross\AceBundle\Entity\User $user
     * @return Recap
     */
    public function setUser(\Albatross\AceBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Albatross\AceBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set actual
     *
     * @param \Albatross\AceBundle\Entity\SurveyNumber $actual
     * @return Recap
     */
    public function setActual(\Albatross\AceBundle\Entity\SurveyNumber $actual = null)
    {
        $this->actual = $actual;

        return $this;
    }

    /**
     * Get actual
     *
     * @return \Albatross\AceBundle\Entity\SurveyNumber 
     */
    public function getActual()
    {
        return $this->actual;
    }

    /**
     * Set infomations
     *
     * @param \Albatross\AceBundle\Entity\Infomation $infomations
     * @return Recap
     */
    public function setInfomations(\Albatross\AceBundle\Entity\Infomation $infomations = null)
    {
        $this->infomations = $infomations;

        return $this;
    }

    /**
     * Get infomations
     *
     * @return \Albatross\AceBundle\Entity\Infomation 
     */
    public function getInfomations()
    {
        return $this->infomations;
    }

    /**
     * Set customwave
     *
     * @param \Albatross\AceBundle\Entity\Customwave $customwave
     * @return Recap
     */
    public function setCustomwave(\Albatross\AceBundle\Entity\Customwave $customwave = null)
    {
        $this->customwave = $customwave;

        return $this;
    }

    /**
     * Get customwave
     *
     * @return \Albatross\AceBundle\Entity\Customwave 
     */
    public function getCustomwave()
    {
        return $this->customwave;
    }

    /**
     * Set planned
     *
     * @param \Albatross\AceBundle\Entity\SurveyNumber $planned
     * @return Recap
     */
    public function setPlanned(\Albatross\AceBundle\Entity\SurveyNumber $planned = null)
    {
        $this->planned = $planned;

        return $this;
    }

    /**
     * Get planned
     *
     * @return \Albatross\AceBundle\Entity\SurveyNumber 
     */
    public function getPlanned()
    {
        return $this->planned;
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
     * Get country
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Add aolquestionnaire
     *
     * @param \Albatross\AceBundle\Entity\Aolquestionnaire $aolquestionnaire
     * @return Recap
     */
    public function addAolquestionnaire(\Albatross\AceBundle\Entity\Aolquestionnaire $aolquestionnaire)
    {
        $this->aolquestionnaire[] = $aolquestionnaire;

        return $this;
    }

    /**
     * Remove aolquestionnaire
     *
     * @param \Albatross\AceBundle\Entity\Aolquestionnaire $aolquestionnaire
     */
    public function removeAolquestionnaire(\Albatross\AceBundle\Entity\Aolquestionnaire $aolquestionnaire)
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
