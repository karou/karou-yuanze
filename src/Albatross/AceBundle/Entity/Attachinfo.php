<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attachinfo
 */
class Attachinfo
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $scope;

    /**
     * @var string
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
    private $comment;

    /**
     * @var \Albatross\AceBundle\Entity\Attachments
     */
    private $attachments;

    /**
     * @var \Albatross\AceBundle\Entity\Bu
     */
    private $bu;


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
     * Set scope
     *
     * @param integer $scope
     * @return Attachinfo
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
     * Set fwstartdate
     *
     * @param string $fwstartdate
     * @return Attachinfo
     */
    public function setFwstartdate($fwstartdate)
    {
        $this->fwstartdate = $fwstartdate;

        return $this;
    }

    /**
     * Get fwstartdate
     *
     * @return string 
     */
    public function getFwstartdate()
    {
        return $this->fwstartdate;
    }

    /**
     * Set fwenddate
     *
     * @param \DateTime $fwenddate
     * @return Attachinfo
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
     * @return Attachinfo
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
     * Set comment
     *
     * @param string $comment
     * @return Attachinfo
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set attachments
     *
     * @param \Albatross\AceBundle\Entity\Attachments $attachments
     * @return Attachinfo
     */
    public function setAttachments(\Albatross\AceBundle\Entity\Attachments $attachments = null)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * Get attachments
     *
     * @return \Albatross\AceBundle\Entity\Attachments 
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Set bu
     *
     * @param \Albatross\AceBundle\Entity\Bu $bu
     * @return Attachinfo
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
     * @var \Albatross\AceBundle\Entity\Project
     */
    private $project;

    /**
     * Set project
     *
     * @param \Albatross\AceBundle\Entity\Project $project
     * @return Attachinfo
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
     * @var integer
     */
    private $formindex;


    /**
     * Set formindex
     *
     * @param integer $formindex
     * @return Attachinfo
     */
    public function setFormindex($formindex)
    {
        $this->formindex = $formindex;

        return $this;
    }

    /**
     * Get formindex
     *
     * @return integer 
     */
    public function getFormindex()
    {
        return $this->formindex;
    }
    /**
     * @var string
     */
    private $reportduedatetext;


    /**
     * Set reportduedatetext
     *
     * @param string $reportduedatetext
     * @return Attachinfo
     */
    public function setReportduedatetext($reportduedatetext)
    {
        $this->reportduedatetext = $reportduedatetext;

        return $this;
    }

    /**
     * Get reportduedatetext
     *
     * @return string 
     */
    public function getReportduedatetext()
    {
        return $this->reportduedatetext;
    }
    /**
     * @var boolean
     */
    private $reporttype;


    /**
     * Set reporttype
     *
     * @param boolean $reporttype
     * @return Attachinfo
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
