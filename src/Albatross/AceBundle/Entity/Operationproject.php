<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Operationproject
 *
 * @ORM\Table(name="operationproject")
 * @ORM\Entity
 */
class Operationproject
{
    /**
     * @var string
     *
     * @ORM\Column(name="fwsdate", type="string", length=20, nullable=false)
     */
    private $fwsdate;

    /**
     * @var string
     *
     * @ORM\Column(name="fwedate", type="string", length=20, nullable=false)
     */
    private $fwedate;

    /**
     * @var string
     *
     * @ORM\Column(name="reportdate", type="string", length=20, nullable=false)
     */
    private $reportdate;

    /**
     * @var integer
     *
     * @ORM\Column(name="survey_num", type="bigint", nullable=true)
     */
    private $surveyNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="assigned_num", type="bigint", nullable=true)
     */
    private $assignedNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="fw_num", type="bigint", nullable=true)
     */
    private $fwNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="editing_num", type="bigint", nullable=true)
     */
    private $editingNum;

    /**
     * @var string
     *
     * @ORM\Column(name="first_visit_date", type="string", length=20, nullable=true)
     */
    private $firstVisitDate;

    /**
     * @var string
     *
     * @ORM\Column(name="last_visit_date", type="string", length=20, nullable=true)
     */
    private $lastVisitDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="info_type", type="bigint", nullable=false)
     */
    private $infoType;

    /**
     * @var string
     *
     * @ORM\Column(name="modified_date", type="string", length=20, nullable=false)
     */
    private $modifiedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="pm", type="string", length=64, nullable=true)
     */
    private $pm;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\Customclient
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Customclient")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customclient_id", referencedColumnName="id")
     * })
     */
    private $customclient;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Country", inversedBy="operationproject")
     * @ORM\JoinTable(name="operationproject_country",
     *   joinColumns={
     *     @ORM\JoinColumn(name="operationproject_id", referencedColumnName="id")
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
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Bu", inversedBy="operationproject")
     * @ORM\JoinTable(name="operationproject_bu",
     *   joinColumns={
     *     @ORM\JoinColumn(name="operationproject_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="bu_id", referencedColumnName="id")
     *   }
     * )
     */
    private $bu;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->country = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bu = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set fwsdate
     *
     * @param string $fwsdate
     * @return Operationproject
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
     * @return Operationproject
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
     * @return Operationproject
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
     * Set surveyNum
     *
     * @param integer $surveyNum
     * @return Operationproject
     */
    public function setSurveyNum($surveyNum)
    {
        $this->surveyNum = $surveyNum;

        return $this;
    }

    /**
     * Get surveyNum
     *
     * @return integer 
     */
    public function getSurveyNum()
    {
        return $this->surveyNum;
    }

    /**
     * Set assignedNum
     *
     * @param integer $assignedNum
     * @return Operationproject
     */
    public function setAssignedNum($assignedNum)
    {
        $this->assignedNum = $assignedNum;

        return $this;
    }

    /**
     * Get assignedNum
     *
     * @return integer 
     */
    public function getAssignedNum()
    {
        return $this->assignedNum;
    }

    /**
     * Set fwNum
     *
     * @param integer $fwNum
     * @return Operationproject
     */
    public function setFwNum($fwNum)
    {
        $this->fwNum = $fwNum;

        return $this;
    }

    /**
     * Get fwNum
     *
     * @return integer 
     */
    public function getFwNum()
    {
        return $this->fwNum;
    }

    /**
     * Set editingNum
     *
     * @param integer $editingNum
     * @return Operationproject
     */
    public function setEditingNum($editingNum)
    {
        $this->editingNum = $editingNum;

        return $this;
    }

    /**
     * Get editingNum
     *
     * @return integer 
     */
    public function getEditingNum()
    {
        return $this->editingNum;
    }

    /**
     * Set firstVisitDate
     *
     * @param string $firstVisitDate
     * @return Operationproject
     */
    public function setFirstVisitDate($firstVisitDate)
    {
        $this->firstVisitDate = $firstVisitDate;

        return $this;
    }

    /**
     * Get firstVisitDate
     *
     * @return string 
     */
    public function getFirstVisitDate()
    {
        return $this->firstVisitDate;
    }

    /**
     * Set lastVisitDate
     *
     * @param string $lastVisitDate
     * @return Operationproject
     */
    public function setLastVisitDate($lastVisitDate)
    {
        $this->lastVisitDate = $lastVisitDate;

        return $this;
    }

    /**
     * Get lastVisitDate
     *
     * @return string 
     */
    public function getLastVisitDate()
    {
        return $this->lastVisitDate;
    }

    /**
     * Set infoType
     *
     * @param integer $infoType
     * @return Operationproject
     */
    public function setInfoType($infoType)
    {
        $this->infoType = $infoType;

        return $this;
    }

    /**
     * Get infoType
     *
     * @return integer 
     */
    public function getInfoType()
    {
        return $this->infoType;
    }

    /**
     * Set modifiedDate
     *
     * @param string $modifiedDate
     * @return Operationproject
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;

        return $this;
    }

    /**
     * Get modifiedDate
     *
     * @return string 
     */
    public function getModifiedDate()
    {
        return $this->modifiedDate;
    }

    /**
     * Set pm
     *
     * @param string $pm
     * @return Operationproject
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set customclient
     *
     * @param \Albatross\AceBundle\Entity\Customclient $customclient
     * @return Operationproject
     */
    public function setCustomclient(\Albatross\AceBundle\Entity\Customclient $customclient = null)
    {
        $this->customclient = $customclient;

        return $this;
    }

    /**
     * Get customclient
     *
     * @return \Albatross\AceBundle\Entity\Customclient 
     */
    public function getCustomclient()
    {
        return $this->customclient;
    }

    /**
     * Set project
     *
     * @param \Albatross\AceBundle\Entity\Project $project
     * @return Operationproject
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
     * Add country
     *
     * @param \Albatross\AceBundle\Entity\Country $country
     * @return Operationproject
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
     * Add bu
     *
     * @param \Albatross\AceBundle\Entity\Bu $bu
     * @return Operationproject
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
     * Get bu
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBu()
    {
        return $this->bu;
    }
}
