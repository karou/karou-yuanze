<?php

namespace Albatross\DailyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rules
 */
class Rules
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $exclude;

    /**
     * @var \Albatross\AceBundle\Entity\Bu
     */
    private $bu;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $status;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $clients;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $countries;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->status = new \Doctrine\Common\Collections\ArrayCollection();
        $this->clients = new \Doctrine\Common\Collections\ArrayCollection();
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set exclude
     *
     * @param boolean $exclude
     * @return Rules
     */
    public function setExclude($exclude)
    {
        $this->exclude = $exclude;

        return $this;
    }

    /**
     * Get exclude
     *
     * @return boolean 
     */
    public function getExclude()
    {
        return $this->exclude;
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
     * Add status
     *
     * @param \Albatross\DailyBundle\Entity\Status $status
     * @return Rules
     */
    public function addStatus(\Albatross\DailyBundle\Entity\Status $status)
    {
        $this->status[] = $status;

        return $this;
    }

    /**
     * Remove status
     *
     * @param \Albatross\DailyBundle\Entity\Status $status
     */
    public function removeStatus(\Albatross\DailyBundle\Entity\Status $status)
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
     * Add clients
     *
     * @param \Albatross\DailyBundle\Entity\Client $clients
     * @return Rules
     */
    public function addClient(\Albatross\DailyBundle\Entity\Client $clients)
    {
        $this->clients[] = $clients;

        return $this;
    }

    /**
     * Remove clients
     *
     * @param \Albatross\DailyBundle\Entity\Client $clients
     */
    public function removeClient(\Albatross\DailyBundle\Entity\Client $clients)
    {
        $this->clients->removeElement($clients);
    }

    /**
     * Get clients
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * Add countries
     *
     * @param \Albatross\AceBundle\Entity\Country $countries
     * @return Rules
     */
    public function addCountry(\Albatross\AceBundle\Entity\Country $countries)
    {
        $this->countries[] = $countries;

        return $this;
    }

    /**
     * Remove countries
     *
     * @param \Albatross\AceBundle\Entity\Country $countries
     */
    public function removeCountry(\Albatross\AceBundle\Entity\Country $countries)
    {
        $this->countries->removeElement($countries);
    }

    /**
     * Get countries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCountries()
    {
        return $this->countries;
    }
    /**
     * @var string
     */
    private $region;

    /**
     * @var string
     */
    private $payrollCurr;


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
     * Set payrollCurr
     *
     * @param string $payrollCurr
     * @return Rules
     */
    public function setPayrollCurr($payrollCurr)
    {
        $this->payrollCurr = $payrollCurr;

        return $this;
    }

    /**
     * Get payrollCurr
     *
     * @return string 
     */
    public function getPayrollCurr()
    {
        return $this->payrollCurr;
    }
    /**
     * @var string
     */
    private $city;


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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $surveys;


    /**
     * Add surveys
     *
     * @param \Albatross\DailyBundle\Entity\Survey $surveys
     * @return Rules
     */
    public function addSurvey(\Albatross\DailyBundle\Entity\Survey $surveys)
    {
        $this->surveys[] = $surveys;

        return $this;
    }

    /**
     * Remove surveys
     *
     * @param \Albatross\DailyBundle\Entity\Survey $surveys
     */
    public function removeSurvey(\Albatross\DailyBundle\Entity\Survey $surveys)
    {
        $this->surveys->removeElement($surveys);
    }

    /**
     * Get surveys
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSurveys()
    {
        return $this->surveys;
    }
    /**
     * @var string
     */
    private $surveyKeyword;


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
     * @var integer
     */
    private $billingRate;


    /**
     * Set billingRate
     *
     * @param integer $billingRate
     * @return Rules
     */
    public function setBillingRate($billingRate)
    {
        $this->billingRate = $billingRate;

        return $this;
    }

    /**
     * Get billingRate
     *
     * @return integer 
     */
    public function getBillingRate()
    {
        return $this->billingRate;
    }
}
