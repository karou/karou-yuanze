<?php

namespace Albatross\OperationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OperationProject
 */
class OperationProject
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $fwsdate;

    /**
     * @var string
     */
    private $fwedate;

    /**
     * @var string
     */
    private $reportdate;

    /**
     * @var integer
     */
    private $survey_num;

    /**
     * @var integer
     */
    private $assigned_num;

    /**
     * @var integer
     */
    private $fw_num;

    /**
     * @var integer
     */
    private $editing_num;

    /**
     * @var string
     */
    private $first_visit_date;

    /**
     * @var string
     */
    private $last_visit_date;

    /**
     * @var string
     */
    private $modified_date;


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
     * Set fwsdate
     *
     * @param string $fwsdate
     * @return OperationProject
     */
    public function setFwsdate($fwsdate)
    {
        $this->fwsdate = $fwsdate;

        return $this;
    }

    /**
     * Get fwsdate
     *
     * @return string 
     */
    public function getFwsdate()
    {
        return $this->fwsdate;
    }

    /**
     * Set fwedate
     *
     * @param string $fwedate
     * @return OperationProject
     */
    public function setFwedate($fwedate)
    {
        $this->fwedate = $fwedate;

        return $this;
    }

    /**
     * Get fwedate
     *
     * @return string 
     */
    public function getFwedate()
    {
        return $this->fwedate;
    }

    /**
     * Set reportdate
     *
     * @param string $reportdate
     * @return OperationProject
     */
    public function setReportdate($reportdate)
    {
        $this->reportdate = $reportdate;

        return $this;
    }

    /**
     * Get reportdate
     *
     * @return string 
     */
    public function getReportdate()
    {
        return $this->reportdate;
    }

    /**
     * Set survey_num
     *
     * @param integer $surveyNum
     * @return OperationProject
     */
    public function setSurveyNum($surveyNum)
    {
        $this->survey_num = $surveyNum;

        return $this;
    }

    /**
     * Get survey_num
     *
     * @return integer 
     */
    public function getSurveyNum()
    {
        return $this->survey_num;
    }

    /**
     * Set assigned_num
     *
     * @param integer $assignedNum
     * @return OperationProject
     */
    public function setAssignedNum($assignedNum)
    {
        $this->assigned_num = $assignedNum;

        return $this;
    }

    /**
     * Get assigned_num
     *
     * @return integer 
     */
    public function getAssignedNum()
    {
        return $this->assigned_num;
    }

    /**
     * Set fw_num
     *
     * @param integer $fwNum
     * @return OperationProject
     */
    public function setFwNum($fwNum)
    {
        $this->fw_num = $fwNum;

        return $this;
    }

    /**
     * Get fw_num
     *
     * @return integer 
     */
    public function getFwNum()
    {
        return $this->fw_num;
    }

    /**
     * Set editing_num
     *
     * @param integer $editingNum
     * @return OperationProject
     */
    public function setEditingNum($editingNum)
    {
        $this->editing_num = $editingNum;

        return $this;
    }

    /**
     * Get editing_num
     *
     * @return integer 
     */
    public function getEditingNum()
    {
        return $this->editing_num;
    }

    /**
     * Set first_visit_date
     *
     * @param string $firstVisitDate
     * @return OperationProject
     */
    public function setFirstVisitDate($firstVisitDate)
    {
        $this->first_visit_date = $firstVisitDate;

        return $this;
    }

    /**
     * Get first_visit_date
     *
     * @return string 
     */
    public function getFirstVisitDate()
    {
        return $this->first_visit_date;
    }

    /**
     * Set last_visit_date
     *
     * @param string $lastVisitDate
     * @return OperationProject
     */
    public function setLastVisitDate($lastVisitDate)
    {
        $this->last_visit_date = $lastVisitDate;

        return $this;
    }

    /**
     * Get last_visit_date
     *
     * @return string 
     */
    public function getLastVisitDate()
    {
        return $this->last_visit_date;
    }

    /**
     * Set modified_date
     *
     * @param string $modifiedDate
     * @return OperationProject
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modified_date = $modifiedDate;

        return $this;
    }

    /**
     * Get modified_date
     *
     * @return string 
     */
    public function getModifiedDate()
    {
        return $this->modified_date;
    }
    /**
     * @var \Albatross\AceBundle\Entity\Project
     */
    private $project;

    /**
     * @var \Albatross\AceBundle\Entity\Bu
     */
    private $bu;

    /**
     * @var \Albatross\AceBundle\Entity\Country
     */
    private $country;

    /**
     * @var \Albatross\CustomBundle\Entity\Customclient
     */
    private $customclient;


    /**
     * Set project
     *
     * @param \Albatross\AceBundle\Entity\Project $project
     * @return OperationProject
     */
    public function setProject(\Albatross\AceBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Albatross\AceBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set customclient
     *
     * @param \Albatross\CustomBundle\Entity\Customclient $customclient
     * @return OperationProject
     */
    public function setCustomclient(\Albatross\CustomBundle\Entity\Customclient $customclient = null)
    {
        $this->customclient = $customclient;

        return $this;
    }

    /**
     * Get customclient
     *
     * @return \Albatross\CustomBundle\Entity\Customclient 
     */
    public function getCustomclient()
    {
        return $this->customclient;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bu = new \Doctrine\Common\Collections\ArrayCollection();
        $this->country = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add bu
     *
     * @param \Albatross\AceBundle\Entity\Bu $bu
     * @return OperationProject
     */
    public function addBu(\Albatross\AceBundle\Entity\Bu $bu)
    {
        $this->bu[] = $bu;

        return $this;
    }

    /**
     * Remove bu
     *
     * @param \Albatross\AceBundle\Entity\Bu $bu
     */
    public function removeBu(\Albatross\AceBundle\Entity\Bu $bu)
    {
        $this->bu->removeElement($bu);
    }

    /**
     * Add country
     *
     * @param \Albatross\AceBundle\Entity\Country $country
     * @return OperationProject
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
     * @var string
     */
    private $pm;


    /**
     * Set pm
     *
     * @param string $pm
     * @return OperationProject
     */
    public function setPm($pm)
    {
        $this->pm = $pm;

        return $this;
    }

    /**
     * Get pm
     *
     * @return string 
     */
    public function getPm()
    {
        return $this->pm;
    }

    /**
     * Get bu
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBu()
    {
        return $this->bu;
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
     * @var integer
     */
    private $info_type;


    /**
     * Set info_type
     *
     * @param integer $infoType
     * @return OperationProject
     */
    public function setInfoType($infoType)
    {
        $this->info_type = $infoType;

        return $this;
    }

    /**
     * Get info_type
     *
     * @return integer 
     */
    public function getInfoType()
    {
        return $this->info_type;
    }
}
