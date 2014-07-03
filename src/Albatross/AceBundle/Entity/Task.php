<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 */
class Task
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $resume;

    /**
     * @var integer
     */
    private $number;

    /**
     * @var string
     */
    private $status;

    /**
     * @var integer
     */
    private $percentageDone;

    /**
     * @var float
     */
    private $actualPercentageDone;

    /**
     * @var \DateTime
     */
    private $createdDate;

    /**
     * @var integer
     */
    private $aceId;

    /**
     * @var \Albatross\AceBundle\Entity\Project
     */
    private $project;

    /**
     * @var \Albatross\AceBundle\Entity\Group
     */
    private $group;

    /**
     * @var \Albatross\AceBundle\Entity\TaskType
     */
    private $taskType;


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
     * Set resume
     *
     * @param string $resume
     * @return Task
     */
    public function setResume($resume)
    {
        $this->resume = $resume;
    
        return $this;
    }

    /**
     * Get resume
     *
     * @return string 
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return Task
     */
    public function setNumber($number)
    {
        $this->number = $number;
    
        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set percentageDone
     *
     * @param integer $percentageDone
     * @return Task
     */
    public function setPercentageDone($percentageDone)
    {
        $this->percentageDone = $percentageDone;
    
        return $this;
    }

    /**
     * Get percentageDone
     *
     * @return integer 
     */
    public function getPercentageDone()
    {
        return $this->percentageDone;
    }

    /**
     * Set actualPercentageDone
     *
     * @param float $actualPercentageDone
     * @return Task
     */
    public function setActualPercentageDone($actualPercentageDone)
    {
        $this->actualPercentageDone = $actualPercentageDone;
    
        return $this;
    }

    /**
     * Get actualPercentageDone
     *
     * @return float 
     */
    public function getActualPercentageDone()
    {
        return $this->actualPercentageDone;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return Task
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    
        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime 
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set aceId
     *
     * @param integer $aceId
     * @return Task
     */
    public function setAceId($aceId)
    {
        $this->aceId = $aceId;
    
        return $this;
    }

    /**
     * Get aceId
     *
     * @return integer 
     */
    public function getAceId()
    {
        return $this->aceId;
    }

    /**
     * Set project
     *
     * @param \Albatross\AceBundle\Entity\Project $project
     * @return Task
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
     * Set group
     *
     * @param \Albatross\AceBundle\Entity\Group $group
     * @return Task
     */
    public function setGroup(\Albatross\AceBundle\Entity\Group $group = null)
    {
        $this->group = $group;
    
        return $this;
    }

    /**
     * Get group
     *
     * @return \Albatross\AceBundle\Entity\Group 
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set taskType
     *
     * @param \Albatross\AceBundle\Entity\TaskType $taskType
     * @return Task
     */
    public function setTaskType(\Albatross\AceBundle\Entity\TaskType $taskType = null)
    {
        $this->taskType = $taskType;
    
        return $this;
    }

    /**
     * Get taskType
     *
     * @return \Albatross\AceBundle\Entity\TaskType 
     */
    public function getTaskType()
    {
        return $this->taskType;
    }
    
    public function __toString() {
        return $this->id;
    }
    /**
     * @var integer
     */
    private $aolPercent;


    /**
     * Set aolPercent
     *
     * @param integer $aolPercent
     * @return Task
     */
    public function setAolPercent($aolPercent)
    {
        $this->aolPercent = $aolPercent;

        return $this;
    }

    /**
     * Get aolPercent
     *
     * @return integer 
     */
    public function getAolPercent()
    {
        return $this->aolPercent;
    }
    /**
     * @var integer
     */
    private $statusId;


    /**
     * Set statusId
     *
     * @param integer $statusId
     * @return Task
     */
    public function setStatusId($statusId)
    {
        $this->statusId = $statusId;

        return $this;
    }

    /**
     * Get statusId
     *
     * @return integer 
     */
    public function getStatusId()
    {
        return $this->statusId;
    }
    /**
     * @var int
     */
    private $updatedaol;


    /**
     * Set updatedaol
     *
     * @param $updatedaol
     * @return Task
     */
    public function setUpdatedaol($updatedaol)
    {
        $this->updatedaol = $updatedaol;

        return $this;
    }

    /**
     * Get updatedaol
     *
     * @return \int 
     */
    public function getUpdatedaol()
    {
        return $this->updatedaol;
    }
    /**
     * @var \DateTime
     */
    private $fwstartdate;

    /**
     * @var \DateTime
     */
    private $fwenddate;

    /**
     * @var \DateTime
     */
    private $reportduedate;

    /**
     * @var integer
     */
    private $scope;

    /**
     * @var string
     */
    private $pm;

    /**
     * Set fwstartdate
     *
     * @param \DateTime $fwstartdate
     * @return Task
     */
    public function setFwstartdate($fwstartdate)
    {
        $this->fwstartdate = $fwstartdate;

        return $this;
    }

    /**
     * Get fwstartdate
     *
     * @return \DateTime 
     */
    public function getFwstartdate()
    {
        return $this->fwstartdate;
    }

    /**
     * Set fwenddate
     *
     * @param \DateTime $fwenddate
     * @return Task
     */
    public function setFwenddate($fwenddate)
    {
        $this->fwenddate = $fwenddate;

        return $this;
    }

    /**
     * Get fwenddate
     *
     * @return \DateTime 
     */
    public function getFwenddate()
    {
        return $this->fwenddate;
    }

    /**
     * Set reportduedate
     *
     * @param \DateTime $reportduedate
     * @return Task
     */
    public function setReportduedate($reportduedate)
    {
        $this->reportduedate = $reportduedate;

        return $this;
    }

    /**
     * Get reportduedate
     *
     * @return \DateTime 
     */
    public function getReportduedate()
    {
        return $this->reportduedate;
    }

    /**
     * Set scope
     *
     * @param integer $scope
     * @return Task
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return integer 
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set pm
     *
     * @param string $pm
     * @return Task
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
     * @var integer
     */
    private $step;


    /**
     * Set step
     *
     * @param integer $step
     * @return Task
     */
    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Get step
     *
     * @return integer 
     */
    public function getStep()
    {
        return $this->step;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $forecast;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->forecast = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add forecast
     *
     * @param \Albatross\AceBundle\Entity\Forecast $forecast
     * @return Task
     */
    public function addForecast(\Albatross\AceBundle\Entity\Forecast $forecast)
    {
        $this->forecast[] = $forecast;

        return $this;
    }

    /**
     * Remove forecast
     *
     * @param \Albatross\AceBundle\Entity\Forecast $forecast
     */
    public function removeForecast(\Albatross\AceBundle\Entity\Forecast $forecast)
    {
        $this->forecast->removeElement($forecast);
    }

    /**
     * Get forecast
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getForecast()
    {
        return $this->forecast;
    }
    /**
     * @var boolean
     */
    private $updated;


    /**
     * Set updated
     *
     * @param boolean $updated
     * @return Task
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return boolean 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
    /**
     * @var string
     */
    private $projectNumber;


    /**
     * Set projectNumber
     *
     * @param string $projectNumber
     * @return Task
     */
    public function setProjectNumber($projectNumber)
    {
        $this->projectNumber = $projectNumber;
    
        return $this;
    }

    /**
     * Get projectNumber
     *
     * @return string 
     */
    public function getProjectNumber()
    {
        return $this->projectNumber;
    }
}
