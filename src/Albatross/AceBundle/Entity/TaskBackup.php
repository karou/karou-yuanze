<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskBackup
 *
 * @ORM\Table(name="task_backup")
 * @ORM\Entity
 */
class TaskBackup
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
     * @return TaskBackup
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
     * @return TaskBackup
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
     * @return TaskBackup
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
     * @return TaskBackup
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
     * @return TaskBackup
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
     * @return TaskBackup
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
     * @return TaskBackup
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
     * @return TaskBackup
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
     * @return TaskBackup
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
     * @return TaskBackup
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
     * @return TaskBackup
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
     * @return TaskBackup
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
