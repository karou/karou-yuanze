<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rules
 *
 * @ORM\Table(name="rules")
 * @ORM\Entity
 */
class Rules
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="state", type="boolean", nullable=false)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=255, nullable=true)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="payrollCurr", type="string", length=255, nullable=true)
     */
    private $payrollcurr;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="survey_keyword", type="string", length=255, nullable=true)
     */
    private $surveyKeyword;

    /**
     * @var integer
     *
     * @ORM\Column(name="billingRate", type="bigint", nullable=true)
     */
    private $billingrate;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\Bu
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Bu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bu_id", referencedColumnName="id")
     * })
     */
    private $bu;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Survey", inversedBy="rules")
     * @ORM\JoinTable(name="rules_survey",
     *   joinColumns={
     *     @ORM\JoinColumn(name="rules_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="survey_id", referencedColumnName="id")
     *   }
     * )
     */
    private $survey;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Status", inversedBy="rules")
     * @ORM\JoinTable(name="rules_status",
     *   joinColumns={
     *     @ORM\JoinColumn(name="rules_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     *   }
     * )
     */
    private $status;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Country", inversedBy="rules")
     * @ORM\JoinTable(name="rules_countries",
     *   joinColumns={
     *     @ORM\JoinColumn(name="rules_id", referencedColumnName="id")
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
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Client", inversedBy="rules")
     * @ORM\JoinTable(name="rules_clients",
     *   joinColumns={
     *     @ORM\JoinColumn(name="rules_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     *   }
     * )
     */
    private $client;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->survey = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = new \Doctrine\Common\Collections\ArrayCollection();
        $this->country = new \Doctrine\Common\Collections\ArrayCollection();
        $this->client = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set state
     *
     * @param boolean $state
     * @return Rules
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return boolean 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return Rules
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set payrollcurr
     *
     * @param string $payrollcurr
     * @return Rules
     */
    public function setPayrollcurr($payrollcurr)
    {
        $this->payrollcurr = $payrollcurr;

        return $this;
    }

    /**
     * Get payrollcurr
     *
     * @return string 
     */
    public function getPayrollcurr()
    {
        return $this->payrollcurr;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Rules
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
     * Set surveyKeyword
     *
     * @param string $surveyKeyword
     * @return Rules
     */
    public function setSurveyKeyword($surveyKeyword)
    {
        $this->surveyKeyword = $surveyKeyword;

        return $this;
    }

    /**
     * Get surveyKeyword
     *
     * @return string 
     */
    public function getSurveyKeyword()
    {
        return $this->surveyKeyword;
    }

    /**
     * Set billingrate
     *
     * @param integer $billingrate
     * @return Rules
     */
    public function setBillingrate($billingrate)
    {
        $this->billingrate = $billingrate;

        return $this;
    }

    /**
     * Get billingrate
     *
     * @return integer 
     */
    public function getBillingrate()
    {
        return $this->billingrate;
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
     * Set bu
     *
     * @param \Albatross\AceBundle\Entity\Bu $bu
     * @return Rules
     */
    public function setBu(\Albatross\AceBundle\Entity\Bu $bu = null)
    {
        $this->bu = $bu;

        return $this;
    }

    /**
     * Get bu
     *
     * @return \Albatross\AceBundle\Entity\Bu 
     */
    public function getBu()
    {
        return $this->bu;
    }

    /**
     * Add survey
     *
     * @param \Albatross\AceBundle\Entity\Survey $survey
     * @return Rules
     */
    public function addSurvey(\Albatross\AceBundle\Entity\Survey $survey)
    {
        $this->survey[] = $survey;

        return $this;
    }

    /**
     * Remove survey
     *
     * @param \Albatross\AceBundle\Entity\Survey $survey
     */
    public function removeSurvey(\Albatross\AceBundle\Entity\Survey $survey)
    {
        $this->survey->removeElement($survey);
    }

    /**
     * Get survey
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Add status
     *
     * @param \Albatross\AceBundle\Entity\Status $status
     * @return Rules
     */
    public function addStatus(\Albatross\AceBundle\Entity\Status $status)
    {
        $this->status[] = $status;

        return $this;
    }

    /**
     * Remove status
     *
     * @param \Albatross\AceBundle\Entity\Status $status
     */
    public function removeStatus(\Albatross\AceBundle\Entity\Status $status)
    {
        $this->status->removeElement($status);
    }

    /**
     * Get status
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add country
     *
     * @param \Albatross\AceBundle\Entity\Country $country
     * @return Rules
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
     * Add client
     *
     * @param \Albatross\AceBundle\Entity\Client $client
     * @return Rules
     */
    public function addClient(\Albatross\AceBundle\Entity\Client $client)
    {
        $this->client[] = $client;

        return $this;
    }

    /**
     * Remove client
     *
     * @param \Albatross\AceBundle\Entity\Client $client
     */
    public function removeClient(\Albatross\AceBundle\Entity\Client $client)
    {
        $this->client->removeElement($client);
    }

    /**
     * Get client
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClient()
    {
        return $this->client;
    }
}
