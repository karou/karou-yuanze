<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Forecast
 */
class Forecast
{
    /**
     * @var integer
     */
    private $id;

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
     * @var string
     */
    private $scope;

    /**
     * @var \DateTime
     */
    private $edittime;

    /**
     * @var \Albatross\AceBundle\Entity\Task
     */
    private $task;

    /**
     * @var \Albatross\UserBundle\Entity\User
     */
    private $user;


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
     * Set fwstartdate
     *
     * @param \DateTime $fwstartdate
     * @return Forecast
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
     * @return Forecast
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
     * @return Forecast
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

    /**
     * Set user
     *
     * @param \Albatross\UserBundle\Entity\User $user
     * @return Forecast
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
     * @var integer
     */
    private $editor;


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
     * @var string
     */
    private $reportduetext;


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
     * @var boolean
     */
    private $reporttype;


    /**
     * Set reporttype
     *
     * @param boolean $reporttype
     * @return Forecast
     */
    public function setReporttype($reporttype)
    {
        $this->reporttype = $reporttype;

        return $this;
    }

    /**
     * Get reporttype
     *
     * @return boolean 
     */
    public function getReporttype()
    {
        return $this->reporttype;
    }
}
