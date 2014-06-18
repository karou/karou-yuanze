<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity
 */
class Task
{
    /**
     * @var integer
     *
     * @ORM\Column(name="project_id", type="bigint", nullable=true)
     */
    private $projectId;

    /**
     * @var integer
     *
     * @ORM\Column(name="group_id", type="bigint", nullable=true)
     */
    private $groupId;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="string", length=255, nullable=false)
     */
    private $resume;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="bigint", nullable=false)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="percentage_done", type="bigint", nullable=false)
     */
    private $percentageDone;

    /**
     * @var float
     *
     * @ORM\Column(name="actual_percentage_done", type="float", nullable=false)
     */
    private $actualPercentageDone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="ace_id", type="bigint", nullable=false)
     */
    private $aceId;

    /**
     * @var integer
     *
     * @ORM\Column(name="task_type_id", type="bigint", nullable=true)
     */
    private $taskTypeId;

    /**
     * @var integer
     *
     * @ORM\Column(name="aol_percent", type="bigint", nullable=true)
     */
    private $aolPercent;

    /**
     * @var integer
     *
     * @ORM\Column(name="status_id", type="bigint", nullable=false)
     */
    private $statusId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="updated_aol", type="boolean", nullable=false)
     */
    private $updatedAol;

    /**
     * @var string
     *
     * @ORM\Column(name="fw_start_date", type="string", length=255, nullable=true)
     */
    private $fwStartDate;

    /**
     * @var string
     *
     * @ORM\Column(name="fw_end_date", type="string", length=255, nullable=true)
     */
    private $fwEndDate;

    /**
     * @var string
     *
     * @ORM\Column(name="report_due_date", type="string", length=255, nullable=true)
     */
    private $reportDueDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="scope", type="bigint", nullable=true)
     */
    private $scope;

    /**
     * @var string
     *
     * @ORM\Column(name="pm", type="string", length=255, nullable=true)
     */
    private $pm;

    /**
     * @var string
     *
     * @ORM\Column(name="project_number", type="string", length=255, nullable=true)
     */
    private $projectNumber;

    /**
     * @var boolean
     *
     * @ORM\Column(name="updated", type="boolean", nullable=false)
     */
    private $updated;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set projectId
     *
     * @param integer $projectId
     * @return Task
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Get projectId
     *
     * @return integer 
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Set groupId
     *
     * @param integer $groupId
     * @return Task
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * Get groupId
     *
     * @return integer 
     */
    public function getGroupId()
    {
        return $this->groupId;
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
     * Set taskTypeId
     *
     * @param integer $taskTypeId
     * @return Task
     */
    public function setTaskTypeId($taskTypeId)
    {
        $this->taskTypeId = $taskTypeId;

        return $this;
    }

    /**
     * Get taskTypeId
     *
     * @return integer 
     */
    public function getTaskTypeId()
    {
        return $this->taskTypeId;
    }

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
     * Set updatedAol
     *
     * @param boolean $updatedAol
     * @return Task
     */
    public function setUpdatedAol($updatedAol)
    {
        $this->updatedAol = $updatedAol;

        return $this;
    }

    /**
     * Get updatedAol
     *
     * @return boolean 
     */
    public function getUpdatedAol()
    {
        return $this->updatedAol;
    }

    /**
     * Set fwStartDate
     *
     * @param string $fwStartDate
     * @return Task
     */
    public function setFwStartDate($fwStartDate)
    {
        $this->fwStartDate = $fwStartDate;

        return $this;
    }

    /**
     * Get fwStartDate
     *
     * @return string 
     */
    public function getFwStartDate()
    {
        return $this->fwStartDate;
    }

    /**
     * Set fwEndDate
     *
     * @param string $fwEndDate
     * @return Task
     */
    public function setFwEndDate($fwEndDate)
    {
        $this->fwEndDate = $fwEndDate;

        return $this;
    }

    /**
     * Get fwEndDate
     *
     * @return string 
     */
    public function getFwEndDate()
    {
        return $this->fwEndDate;
    }

    /**
     * Set reportDueDate
     *
     * @param string $reportDueDate
     * @return Task
     */
    public function setReportDueDate($reportDueDate)
    {
        $this->reportDueDate = $reportDueDate;

        return $this;
    }

    /**
     * Get reportDueDate
     *
     * @return string 
     */
    public function getReportDueDate()
    {
        return $this->reportDueDate;
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
