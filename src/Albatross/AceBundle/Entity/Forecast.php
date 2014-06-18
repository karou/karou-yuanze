<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Forecast
 *
 * @ORM\Table(name="forecast")
 * @ORM\Entity
 */
class Forecast
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fw_start_date", type="date", nullable=true)
     */
    private $fwStartDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fw_end_date", type="date", nullable=true)
     */
    private $fwEndDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="report_due_date", type="date", nullable=true)
     */
    private $reportDueDate;

    /**
     * @var string
     *
     * @ORM\Column(name="scope", type="string", length=255, nullable=true)
     */
    private $scope;

    /**
     * @var integer
     *
     * @ORM\Column(name="editor", type="bigint", nullable=false)
     */
    private $editor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="edittime", type="datetime", nullable=false)
     */
    private $edittime;

    /**
     * @var string
     *
     * @ORM\Column(name="reportduetext", type="string", length=255, nullable=true)
     */
    private $reportduetext;

    /**
     * @var boolean
     *
     * @ORM\Column(name="report_type", type="boolean", nullable=false)
     */
    private $reportType;

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
     * @var \Albatross\AceBundle\Entity\Task
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Task")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     * })
     */
    private $task;



    /**
     * Set fwStartDate
     *
     * @param \DateTime $fwStartDate
     * @return Forecast
     */
    public function setFwStartDate($fwStartDate)
    {
        $this->fwStartDate = $fwStartDate;

        return $this;
    }

    /**
     * Get fwStartDate
     *
     * @return \DateTime 
     */
    public function getFwStartDate()
    {
        return $this->fwStartDate;
    }

    /**
     * Set fwEndDate
     *
     * @param \DateTime $fwEndDate
     * @return Forecast
     */
    public function setFwEndDate($fwEndDate)
    {
        $this->fwEndDate = $fwEndDate;

        return $this;
    }

    /**
     * Get fwEndDate
     *
     * @return \DateTime 
     */
    public function getFwEndDate()
    {
        return $this->fwEndDate;
    }

    /**
     * Set reportDueDate
     *
     * @param \DateTime $reportDueDate
     * @return Forecast
     */
    public function setReportDueDate($reportDueDate)
    {
        $this->reportDueDate = $reportDueDate;

        return $this;
    }

    /**
     * Get reportDueDate
     *
     * @return \DateTime 
     */
    public function getReportDueDate()
    {
        return $this->reportDueDate;
    }

    /**
     * Set scope
     *
     * @param string $scope
     * @return Forecast
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return string 
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set editor
     *
     * @param integer $editor
     * @return Forecast
     */
    public function setEditor($editor)
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * Get editor
     *
     * @return integer 
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * Set edittime
     *
     * @param \DateTime $edittime
     * @return Forecast
     */
    public function setEdittime($edittime)
    {
        $this->edittime = $edittime;

        return $this;
    }

    /**
     * Get edittime
     *
     * @return \DateTime 
     */
    public function getEdittime()
    {
        return $this->edittime;
    }

    /**
     * Set reportduetext
     *
     * @param string $reportduetext
     * @return Forecast
     */
    public function setReportduetext($reportduetext)
    {
        $this->reportduetext = $reportduetext;

        return $this;
    }

    /**
     * Get reportduetext
     *
     * @return string 
     */
    public function getReportduetext()
    {
        return $this->reportduetext;
    }

    /**
     * Set reportType
     *
     * @param boolean $reportType
     * @return Forecast
     */
    public function setReportType($reportType)
    {
        $this->reportType = $reportType;

        return $this;
    }

    /**
     * Get reportType
     *
     * @return boolean 
     */
    public function getReportType()
    {
        return $this->reportType;
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
     * @return Forecast
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
     * Set task
     *
     * @param \Albatross\AceBundle\Entity\Task $task
     * @return Forecast
     */
    public function setTask(\Albatross\AceBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \Albatross\AceBundle\Entity\Task 
     */
    public function getTask()
    {
        return $this->task;
    }
}
