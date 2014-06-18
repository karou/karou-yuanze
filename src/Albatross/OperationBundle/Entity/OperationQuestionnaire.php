<?php

namespace Albatross\OperationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OperationQuestionnaire
 */
class OperationQuestionnaire
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
     * @var int
     */
    private $info_type;

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
     * @return OperationQuestionnaire
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
     * @return OperationQuestionnaire
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
     * @return OperationQuestionnaire
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
     * @return OperationQuestionnaire
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
     * @return OperationQuestionnaire
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
     * @return OperationQuestionnaire
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
     * @return OperationQuestionnaire
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
     * @return OperationQuestionnaire
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
     * @return OperationQuestionnaire
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
     * Set info_type
     *
     * @param \int $infoType
     * @return OperationQuestionnaire
     */
    public function setInfoType(\int $infoType)
    {
        $this->info_type = $infoType;

        return $this;
    }

    /**
     * Get info_type
     *
     * @return \int 
     */
    public function getInfoType()
    {
        return $this->info_type;
    }

    /**
     * Set modified_date
     *
     * @param string $modifiedDate
     * @return OperationQuestionnaire
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
     * @var \Albatross\AceBundle\Entity\Campaign
     */
    private $campaign;

    /**
     * @var \Albatross\AceBundle\Entity\Questionnaire
     */
    private $questionnaire;


    /**
     * @var \Albatross\CustomBundle\Entity\Customclient
     */
    private $customclient;


    /**
     * Set campaign
     *
     * @param \Albatross\AceBundle\Entity\Campaign $campaign
     * @return OperationQuestionnaire
     */
    public function setCampaign(\Albatross\AceBundle\Entity\Campaign $campaign = null)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return \Albatross\AceBundle\Entity\Campaign 
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * Set questionnaire
     *
     * @param \Albatross\AceBundle\Entity\Questionnaire $questionnaire
     * @return OperationQuestionnaire
     */
    public function setQuestionnaire(\Albatross\AceBundle\Entity\Questionnaire $questionnaire = null)
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }

    /**
     * Get questionnaire
     *
     * @return \Albatross\AceBundle\Entity\Questionnaire 
     */
    public function getQuestionnaire()
    {
        return $this->questionnaire;
    }

    /**
     * Set customclient
     *
     * @param \Albatross\CustomBundle\Entity\Customclient $customclient
     * @return OperationQuestionnaire
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $bu;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $country;

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
     * @return OperationQuestionnaire
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

    /**
     * Add country
     *
     * @param \Albatross\AceBundle\Entity\Country $country
     * @return OperationQuestionnaire
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
     * Add customclient
     *
     * @param \Albatross\CustomBundle\Entity\Customclient $customclient
     * @return OperationQuestionnaire
     */
    public function addCustomclient(\Albatross\CustomBundle\Entity\Customclient $customclient)
    {
        $this->customclient[] = $customclient;

        return $this;
    }

    /**
     * Remove customclient
     *
     * @param \Albatross\CustomBundle\Entity\Customclient $customclient
     */
    public function removeCustomclient(\Albatross\CustomBundle\Entity\Customclient $customclient)
    {
        $this->customclient->removeElement($customclient);
    }


    /**
     * @var integer
     */
    private $qid;

    /**
     * @var integer
     */
    private $campid;

    /**
     * @var string
     */
    private $questionnaire_name;



    /**
     * Set qid
     *
     * @param integer $qid
     * @return OperationQuestionnaire
     */
    public function setQid($qid)
    {
        $this->qid = $qid;

        return $this;
    }

    /**
     * Get qid
     *
     * @return integer 
     */
    public function getQid()
    {
        return $this->qid;
    }

    /**
     * Set campid
     *
     * @param integer $campid
     * @return OperationQuestionnaire
     */
    public function setCampid($campid)
    {
        $this->campid = $campid;

        return $this;
    }

    /**
     * Get campid
     *
     * @return integer 
     */
    public function getCampid()
    {
        return $this->campid;
    }

    /**
     * Set questionnaire_name
     *
     * @param string $questionnaireName
     * @return OperationQuestionnaire
     */
    public function setQuestionnaireName($questionnaireName)
    {
        $this->questionnaire_name = $questionnaireName;

        return $this;
    }

    /**
     * Get questionnaire_name
     *
     * @return string 
     */
    public function getQuestionnaireName()
    {
        return $this->questionnaire_name;
    }
}
