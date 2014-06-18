<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customfield
 *
 * @ORM\Table(name="customfield")
 * @ORM\Entity
 */
class Customfield
{
    /**
     * @var integer
     *
     * @ORM\Column(name="material_name", type="bigint", nullable=true)
     */
    private $materialName;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="fieldtype", type="string", length=255, nullable=false)
     */
    private $fieldtype;

    /**
     * @var string
     *
     * @ORM\Column(name="report_type", type="string", length=255, nullable=true)
     */
    private $reportType;

    /**
     * @var boolean
     *
     * @ORM\Column(name="report_executive", type="boolean", nullable=false)
     */
    private $reportExecutive;

    /**
     * @var string
     *
     * @ORM\Column(name="report_zone", type="string", length=255, nullable=true)
     */
    private $reportZone;

    /**
     * @var boolean
     *
     * @ORM\Column(name="main_brief", type="boolean", nullable=false)
     */
    private $mainBrief;

    /**
     * @var string
     *
     * @ORM\Column(name="brief_translation", type="string", length=255, nullable=true)
     */
    private $briefTranslation;

    /**
     * @var string
     *
     * @ORM\Column(name="submittime", type="string", length=255, nullable=false)
     */
    private $submittime;

    /**
     * @var string
     *
     * @ORM\Column(name="mm_brand", type="string", length=255, nullable=true)
     */
    private $mmBrand;

    /**
     * @var string
     *
     * @ORM\Column(name="mm_date", type="string", length=255, nullable=true)
     */
    private $mmDate;

    /**
     * @var string
     *
     * @ORM\Column(name="mm_address", type="string", length=255, nullable=true)
     */
    private $mmAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="mm_purpose", type="string", length=255, nullable=true)
     */
    private $mmPurpose;

    /**
     * @var string
     *
     * @ORM\Column(name="mm_nextstep", type="string", length=255, nullable=true)
     */
    private $mmNextstep;

    /**
     * @var string
     *
     * @ORM\Column(name="mm_agenda_of_the_meeting", type="text", nullable=true)
     */
    private $mmAgendaOfTheMeeting;

    /**
     * @var string
     *
     * @ORM\Column(name="mm_clients_feedback", type="text", nullable=true)
     */
    private $mmClientsFeedback;

    /**
     * @var string
     *
     * @ORM\Column(name="mm_comments", type="text", nullable=true)
     */
    private $mmComments;

    /**
     * @var boolean
     *
     * @ORM\Column(name="client_confirmation", type="boolean", nullable=true)
     */
    private $clientConfirmation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pm_confirmation", type="boolean", nullable=true)
     */
    private $pmConfirmation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="upload_waiting_clonage", type="boolean", nullable=true)
     */
    private $uploadWaitingClonage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="proofreading", type="boolean", nullable=true)
     */
    private $proofreading;

    /**
     * @var string
     *
     * @ORM\Column(name="client_signature", type="string", length=255, nullable=true)
     */
    private $clientSignature;

    /**
     * @var string
     *
     * @ORM\Column(name="pm_signature", type="string", length=255, nullable=true)
     */
    private $pmSignature;

    /**
     * @var string
     *
     * @ORM\Column(name="upload_waiting_clonage_signature", type="string", length=255, nullable=true)
     */
    private $uploadWaitingClonageSignature;

    /**
     * @var string
     *
     * @ORM\Column(name="proofreading_signature", type="string", length=255, nullable=true)
     */
    private $proofreadingSignature;

    /**
     * @var integer
     *
     * @ORM\Column(name="choosen_type", type="bigint", nullable=true)
     */
    private $choosenType;

    /**
     * @var integer
     *
     * @ORM\Column(name="question_status", type="bigint", nullable=true)
     */
    private $questionStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="path_2", type="string", length=255, nullable=true)
     */
    private $path2;

    /**
     * @var string
     *
     * @ORM\Column(name="path_3", type="string", length=255, nullable=true)
     */
    private $path3;

    /**
     * @var string
     *
     * @ORM\Column(name="path_4", type="string", length=255, nullable=true)
     */
    private $path4;

    /**
     * @var string
     *
     * @ORM\Column(name="client_confirmation_time", type="string", length=255, nullable=true)
     */
    private $clientConfirmationTime;

    /**
     * @var string
     *
     * @ORM\Column(name="question_file1_label", type="string", length=255, nullable=true)
     */
    private $questionFile1Label;

    /**
     * @var string
     *
     * @ORM\Column(name="question_file2_label", type="string", length=255, nullable=true)
     */
    private $questionFile2Label;

    /**
     * @var string
     *
     * @ORM\Column(name="question_file3_label", type="string", length=255, nullable=true)
     */
    private $questionFile3Label;

    /**
     * @var string
     *
     * @ORM\Column(name="question_file4_label", type="string", length=255, nullable=true)
     */
    private $questionFile4Label;

    /**
     * @var boolean
     *
     * @ORM\Column(name="quality_control", type="boolean", nullable=true)
     */
    private $qualityControl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="testing", type="boolean", nullable=true)
     */
    private $testing;

    /**
     * @var string
     *
     * @ORM\Column(name="quality_control_signature", type="string", length=255, nullable=true)
     */
    private $qualityControlSignature;

    /**
     * @var string
     *
     * @ORM\Column(name="testing_signature", type="string", length=255, nullable=true)
     */
    private $testingSignature;

    /**
     * @var string
     *
     * @ORM\Column(name="question_end_time", type="string", length=255, nullable=true)
     */
    private $questionEndTime;

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
     * @var \Albatross\AceBundle\Entity\Customwave
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Customwave")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customwave_id", referencedColumnName="id")
     * })
     */
    private $customwave;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Country", inversedBy="customfield")
     * @ORM\JoinTable(name="customfield_country",
     *   joinColumns={
     *     @ORM\JoinColumn(name="customfield_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     *   }
     * )
     */
    private $country;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->country = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set materialName
     *
     * @param integer $materialName
     * @return Customfield
     */
    public function setMaterialName($materialName)
    {
        $this->materialName = $materialName;

        return $this;
    }

    /**
     * Get materialName
     *
     * @return integer 
     */
    public function getMaterialName()
    {
        return $this->materialName;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Customfield
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set fieldtype
     *
     * @param string $fieldtype
     * @return Customfield
     */
    public function setFieldtype($fieldtype)
    {
        $this->fieldtype = $fieldtype;

        return $this;
    }

    /**
     * Get fieldtype
     *
     * @return string 
     */
    public function getFieldtype()
    {
        return $this->fieldtype;
    }

    /**
     * Set reportType
     *
     * @param string $reportType
     * @return Customfield
     */
    public function setReportType($reportType)
    {
        $this->reportType = $reportType;

        return $this;
    }

    /**
     * Get reportType
     *
     * @return string 
     */
    public function getReportType()
    {
        return $this->reportType;
    }

    /**
     * Set reportExecutive
     *
     * @param boolean $reportExecutive
     * @return Customfield
     */
    public function setReportExecutive($reportExecutive)
    {
        $this->reportExecutive = $reportExecutive;

        return $this;
    }

    /**
     * Get reportExecutive
     *
     * @return boolean 
     */
    public function getReportExecutive()
    {
        return $this->reportExecutive;
    }

    /**
     * Set reportZone
     *
     * @param string $reportZone
     * @return Customfield
     */
    public function setReportZone($reportZone)
    {
        $this->reportZone = $reportZone;

        return $this;
    }

    /**
     * Get reportZone
     *
     * @return string 
     */
    public function getReportZone()
    {
        return $this->reportZone;
    }

    /**
     * Set mainBrief
     *
     * @param boolean $mainBrief
     * @return Customfield
     */
    public function setMainBrief($mainBrief)
    {
        $this->mainBrief = $mainBrief;

        return $this;
    }

    /**
     * Get mainBrief
     *
     * @return boolean 
     */
    public function getMainBrief()
    {
        return $this->mainBrief;
    }

    /**
     * Set briefTranslation
     *
     * @param string $briefTranslation
     * @return Customfield
     */
    public function setBriefTranslation($briefTranslation)
    {
        $this->briefTranslation = $briefTranslation;

        return $this;
    }

    /**
     * Get briefTranslation
     *
     * @return string 
     */
    public function getBriefTranslation()
    {
        return $this->briefTranslation;
    }

    /**
     * Set submittime
     *
     * @param string $submittime
     * @return Customfield
     */
    public function setSubmittime($submittime)
    {
        $this->submittime = $submittime;

        return $this;
    }

    /**
     * Get submittime
     *
     * @return string 
     */
    public function getSubmittime()
    {
        return $this->submittime;
    }

    /**
     * Set mmBrand
     *
     * @param string $mmBrand
     * @return Customfield
     */
    public function setMmBrand($mmBrand)
    {
        $this->mmBrand = $mmBrand;

        return $this;
    }

    /**
     * Get mmBrand
     *
     * @return string 
     */
    public function getMmBrand()
    {
        return $this->mmBrand;
    }

    /**
     * Set mmDate
     *
     * @param string $mmDate
     * @return Customfield
     */
    public function setMmDate($mmDate)
    {
        $this->mmDate = $mmDate;

        return $this;
    }

    /**
     * Get mmDate
     *
     * @return string 
     */
    public function getMmDate()
    {
        return $this->mmDate;
    }

    /**
     * Set mmAddress
     *
     * @param string $mmAddress
     * @return Customfield
     */
    public function setMmAddress($mmAddress)
    {
        $this->mmAddress = $mmAddress;

        return $this;
    }

    /**
     * Get mmAddress
     *
     * @return string 
     */
    public function getMmAddress()
    {
        return $this->mmAddress;
    }

    /**
     * Set mmPurpose
     *
     * @param string $mmPurpose
     * @return Customfield
     */
    public function setMmPurpose($mmPurpose)
    {
        $this->mmPurpose = $mmPurpose;

        return $this;
    }

    /**
     * Get mmPurpose
     *
     * @return string 
     */
    public function getMmPurpose()
    {
        return $this->mmPurpose;
    }

    /**
     * Set mmNextstep
     *
     * @param string $mmNextstep
     * @return Customfield
     */
    public function setMmNextstep($mmNextstep)
    {
        $this->mmNextstep = $mmNextstep;

        return $this;
    }

    /**
     * Get mmNextstep
     *
     * @return string 
     */
    public function getMmNextstep()
    {
        return $this->mmNextstep;
    }

    /**
     * Set mmAgendaOfTheMeeting
     *
     * @param string $mmAgendaOfTheMeeting
     * @return Customfield
     */
    public function setMmAgendaOfTheMeeting($mmAgendaOfTheMeeting)
    {
        $this->mmAgendaOfTheMeeting = $mmAgendaOfTheMeeting;

        return $this;
    }

    /**
     * Get mmAgendaOfTheMeeting
     *
     * @return string 
     */
    public function getMmAgendaOfTheMeeting()
    {
        return $this->mmAgendaOfTheMeeting;
    }

    /**
     * Set mmClientsFeedback
     *
     * @param string $mmClientsFeedback
     * @return Customfield
     */
    public function setMmClientsFeedback($mmClientsFeedback)
    {
        $this->mmClientsFeedback = $mmClientsFeedback;

        return $this;
    }

    /**
     * Get mmClientsFeedback
     *
     * @return string 
     */
    public function getMmClientsFeedback()
    {
        return $this->mmClientsFeedback;
    }

    /**
     * Set mmComments
     *
     * @param string $mmComments
     * @return Customfield
     */
    public function setMmComments($mmComments)
    {
        $this->mmComments = $mmComments;

        return $this;
    }

    /**
     * Get mmComments
     *
     * @return string 
     */
    public function getMmComments()
    {
        return $this->mmComments;
    }

    /**
     * Set clientConfirmation
     *
     * @param boolean $clientConfirmation
     * @return Customfield
     */
    public function setClientConfirmation($clientConfirmation)
    {
        $this->clientConfirmation = $clientConfirmation;

        return $this;
    }

    /**
     * Get clientConfirmation
     *
     * @return boolean 
     */
    public function getClientConfirmation()
    {
        return $this->clientConfirmation;
    }

    /**
     * Set pmConfirmation
     *
     * @param boolean $pmConfirmation
     * @return Customfield
     */
    public function setPmConfirmation($pmConfirmation)
    {
        $this->pmConfirmation = $pmConfirmation;

        return $this;
    }

    /**
     * Get pmConfirmation
     *
     * @return boolean 
     */
    public function getPmConfirmation()
    {
        return $this->pmConfirmation;
    }

    /**
     * Set uploadWaitingClonage
     *
     * @param boolean $uploadWaitingClonage
     * @return Customfield
     */
    public function setUploadWaitingClonage($uploadWaitingClonage)
    {
        $this->uploadWaitingClonage = $uploadWaitingClonage;

        return $this;
    }

    /**
     * Get uploadWaitingClonage
     *
     * @return boolean 
     */
    public function getUploadWaitingClonage()
    {
        return $this->uploadWaitingClonage;
    }

    /**
     * Set proofreading
     *
     * @param boolean $proofreading
     * @return Customfield
     */
    public function setProofreading($proofreading)
    {
        $this->proofreading = $proofreading;

        return $this;
    }

    /**
     * Get proofreading
     *
     * @return boolean 
     */
    public function getProofreading()
    {
        return $this->proofreading;
    }

    /**
     * Set clientSignature
     *
     * @param string $clientSignature
     * @return Customfield
     */
    public function setClientSignature($clientSignature)
    {
        $this->clientSignature = $clientSignature;

        return $this;
    }

    /**
     * Get clientSignature
     *
     * @return string 
     */
    public function getClientSignature()
    {
        return $this->clientSignature;
    }

    /**
     * Set pmSignature
     *
     * @param string $pmSignature
     * @return Customfield
     */
    public function setPmSignature($pmSignature)
    {
        $this->pmSignature = $pmSignature;

        return $this;
    }

    /**
     * Get pmSignature
     *
     * @return string 
     */
    public function getPmSignature()
    {
        return $this->pmSignature;
    }

    /**
     * Set uploadWaitingClonageSignature
     *
     * @param string $uploadWaitingClonageSignature
     * @return Customfield
     */
    public function setUploadWaitingClonageSignature($uploadWaitingClonageSignature)
    {
        $this->uploadWaitingClonageSignature = $uploadWaitingClonageSignature;

        return $this;
    }

    /**
     * Get uploadWaitingClonageSignature
     *
     * @return string 
     */
    public function getUploadWaitingClonageSignature()
    {
        return $this->uploadWaitingClonageSignature;
    }

    /**
     * Set proofreadingSignature
     *
     * @param string $proofreadingSignature
     * @return Customfield
     */
    public function setProofreadingSignature($proofreadingSignature)
    {
        $this->proofreadingSignature = $proofreadingSignature;

        return $this;
    }

    /**
     * Get proofreadingSignature
     *
     * @return string 
     */
    public function getProofreadingSignature()
    {
        return $this->proofreadingSignature;
    }

    /**
     * Set choosenType
     *
     * @param integer $choosenType
     * @return Customfield
     */
    public function setChoosenType($choosenType)
    {
        $this->choosenType = $choosenType;

        return $this;
    }

    /**
     * Get choosenType
     *
     * @return integer 
     */
    public function getChoosenType()
    {
        return $this->choosenType;
    }

    /**
     * Set questionStatus
     *
     * @param integer $questionStatus
     * @return Customfield
     */
    public function setQuestionStatus($questionStatus)
    {
        $this->questionStatus = $questionStatus;

        return $this;
    }

    /**
     * Get questionStatus
     *
     * @return integer 
     */
    public function getQuestionStatus()
    {
        return $this->questionStatus;
    }

    /**
     * Set path2
     *
     * @param string $path2
     * @return Customfield
     */
    public function setPath2($path2)
    {
        $this->path2 = $path2;

        return $this;
    }

    /**
     * Get path2
     *
     * @return string 
     */
    public function getPath2()
    {
        return $this->path2;
    }

    /**
     * Set path3
     *
     * @param string $path3
     * @return Customfield
     */
    public function setPath3($path3)
    {
        $this->path3 = $path3;

        return $this;
    }

    /**
     * Get path3
     *
     * @return string 
     */
    public function getPath3()
    {
        return $this->path3;
    }

    /**
     * Set path4
     *
     * @param string $path4
     * @return Customfield
     */
    public function setPath4($path4)
    {
        $this->path4 = $path4;

        return $this;
    }

    /**
     * Get path4
     *
     * @return string 
     */
    public function getPath4()
    {
        return $this->path4;
    }

    /**
     * Set clientConfirmationTime
     *
     * @param string $clientConfirmationTime
     * @return Customfield
     */
    public function setClientConfirmationTime($clientConfirmationTime)
    {
        $this->clientConfirmationTime = $clientConfirmationTime;

        return $this;
    }

    /**
     * Get clientConfirmationTime
     *
     * @return string 
     */
    public function getClientConfirmationTime()
    {
        return $this->clientConfirmationTime;
    }

    /**
     * Set questionFile1Label
     *
     * @param string $questionFile1Label
     * @return Customfield
     */
    public function setQuestionFile1Label($questionFile1Label)
    {
        $this->questionFile1Label = $questionFile1Label;

        return $this;
    }

    /**
     * Get questionFile1Label
     *
     * @return string 
     */
    public function getQuestionFile1Label()
    {
        return $this->questionFile1Label;
    }

    /**
     * Set questionFile2Label
     *
     * @param string $questionFile2Label
     * @return Customfield
     */
    public function setQuestionFile2Label($questionFile2Label)
    {
        $this->questionFile2Label = $questionFile2Label;

        return $this;
    }

    /**
     * Get questionFile2Label
     *
     * @return string 
     */
    public function getQuestionFile2Label()
    {
        return $this->questionFile2Label;
    }

    /**
     * Set questionFile3Label
     *
     * @param string $questionFile3Label
     * @return Customfield
     */
    public function setQuestionFile3Label($questionFile3Label)
    {
        $this->questionFile3Label = $questionFile3Label;

        return $this;
    }

    /**
     * Get questionFile3Label
     *
     * @return string 
     */
    public function getQuestionFile3Label()
    {
        return $this->questionFile3Label;
    }

    /**
     * Set questionFile4Label
     *
     * @param string $questionFile4Label
     * @return Customfield
     */
    public function setQuestionFile4Label($questionFile4Label)
    {
        $this->questionFile4Label = $questionFile4Label;

        return $this;
    }

    /**
     * Get questionFile4Label
     *
     * @return string 
     */
    public function getQuestionFile4Label()
    {
        return $this->questionFile4Label;
    }

    /**
     * Set qualityControl
     *
     * @param boolean $qualityControl
     * @return Customfield
     */
    public function setQualityControl($qualityControl)
    {
        $this->qualityControl = $qualityControl;

        return $this;
    }

    /**
     * Get qualityControl
     *
     * @return boolean 
     */
    public function getQualityControl()
    {
        return $this->qualityControl;
    }

    /**
     * Set testing
     *
     * @param boolean $testing
     * @return Customfield
     */
    public function setTesting($testing)
    {
        $this->testing = $testing;

        return $this;
    }

    /**
     * Get testing
     *
     * @return boolean 
     */
    public function getTesting()
    {
        return $this->testing;
    }

    /**
     * Set qualityControlSignature
     *
     * @param string $qualityControlSignature
     * @return Customfield
     */
    public function setQualityControlSignature($qualityControlSignature)
    {
        $this->qualityControlSignature = $qualityControlSignature;

        return $this;
    }

    /**
     * Get qualityControlSignature
     *
     * @return string 
     */
    public function getQualityControlSignature()
    {
        return $this->qualityControlSignature;
    }

    /**
     * Set testingSignature
     *
     * @param string $testingSignature
     * @return Customfield
     */
    public function setTestingSignature($testingSignature)
    {
        $this->testingSignature = $testingSignature;

        return $this;
    }

    /**
     * Get testingSignature
     *
     * @return string 
     */
    public function getTestingSignature()
    {
        return $this->testingSignature;
    }

    /**
     * Set questionEndTime
     *
     * @param string $questionEndTime
     * @return Customfield
     */
    public function setQuestionEndTime($questionEndTime)
    {
        $this->questionEndTime = $questionEndTime;

        return $this;
    }

    /**
     * Get questionEndTime
     *
     * @return string 
     */
    public function getQuestionEndTime()
    {
        return $this->questionEndTime;
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
     * @return Customfield
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
     * Set customwave
     *
     * @param \Albatross\AceBundle\Entity\Customwave $customwave
     * @return Customfield
     */
    public function setCustomwave(\Albatross\AceBundle\Entity\Customwave $customwave = null)
    {
        $this->customwave = $customwave;

        return $this;
    }

    /**
     * Get customwave
     *
     * @return \Albatross\AceBundle\Entity\Customwave 
     */
    public function getCustomwave()
    {
        return $this->customwave;
    }

    /**
     * Add country
     *
     * @param \Albatross\AceBundle\Entity\Country $country
     * @return Customfield
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
}
