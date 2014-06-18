<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attachinfo
 *
 * @ORM\Table(name="attachinfo")
 * @ORM\Entity
 */
class Attachinfo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="scope", type="bigint", nullable=true)
     */
    private $scope;

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
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="formindex", type="string", length=20, nullable=false)
     */
    private $formindex;

    /**
     * @var string
     *
     * @ORM\Column(name="report_due_date_text", type="string", length=255, nullable=true)
     */
    private $reportDueDateText;

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
     * @var \Albatross\AceBundle\Entity\Bu
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Bu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bu_id", referencedColumnName="id")
     * })
     */
    private $bu;

    /**
     * @var \Albatross\AceBundle\Entity\Attachments
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Attachments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attachments_id", referencedColumnName="id")
     * })
     */
    private $attachments;

    /**
     * @var \Albatross\AceBundle\Entity\Project
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Project")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;



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
     * Set fwStartDate
     *
     * @param \DateTime $fwStartDate
     * @return Attachinfo
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
     * @return Attachinfo
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
     * @return Attachinfo
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
     * Set formindex
     *
     * @param string $formindex
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
     * @return string 
     */
    public function getFormindex()
    {
        return $this->formindex;
    }

    /**
     * Set reportDueDateText
     *
     * @param string $reportDueDateText
     * @return Attachinfo
     */
    public function setReportDueDateText($reportDueDateText)
    {
        $this->reportDueDateText = $reportDueDateText;

        return $this;
    }

    /**
     * Get reportDueDateText
     *
     * @return string 
     */
    public function getReportDueDateText()
    {
        return $this->reportDueDateText;
    }

    /**
     * Set reportType
     *
     * @param boolean $reportType
     * @return Attachinfo
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
}
