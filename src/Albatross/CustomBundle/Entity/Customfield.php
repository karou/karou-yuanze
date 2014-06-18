<?php

namespace Albatross\CustomBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * Customfield
 */
class Customfield
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $material_name;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $fieldtype;

    /**
     * @var string
     */
    private $report_type;

    /**
     * @var boolean
     */
    private $report_executive;

    /**
     * @var string
     */
    private $report_zone;

    /**
     * @var boolean
     */
    private $main_brief;

    /**
     * @var integer
     */
    private $brief_translation;

    /**
     * @var string
     */
    private $submittime;

    /**
     * @var \Albatross\CustomBundle\Entity\Customwave
     */
    private $customwave;

    /**
     * @var \Albatross\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set material_name
     *
     * @param string $materialName
     * @return Customfield
     */
    public function setMaterialName($materialName)
    {
        $this->material_name = $materialName;

        return $this;
    }

    /**
     * Get material_name
     *
     * @return string 
     */
    public function getMaterialName()
    {
        return $this->material_name;
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
     * Set report_type
     *
     * @param string $reportType
     * @return Customfield
     */
    public function setReportType($reportType)
    {
        $this->report_type = $reportType;

        return $this;
    }

    /**
     * Get report_type
     *
     * @return string 
     */
    public function getReportType()
    {
        return $this->report_type;
    }

    /**
     * Set report_executive
     *
     * @param boolean $reportExecutive
     * @return Customfield
     */
    public function setReportExecutive($reportExecutive)
    {
        $this->report_executive = $reportExecutive;

        return $this;
    }

    /**
     * Get report_executive
     *
     * @return boolean 
     */
    public function getReportExecutive()
    {
        return $this->report_executive;
    }

    /**
     * Set report_zone
     *
     * @param string $reportZone
     * @return Customfield
     */
    public function setReportZone($reportZone)
    {
        $this->report_zone = $reportZone;

        return $this;
    }

    /**
     * Get report_zone
     *
     * @return string 
     */
    public function getReportZone()
    {
        return $this->report_zone;
    }

    /**
     * Set main_brief
     *
     * @param boolean $mainBrief
     * @return Customfield
     */
    public function setMainBrief($mainBrief)
    {
        $this->main_brief = $mainBrief;

        return $this;
    }

    /**
     * Get main_brief
     *
     * @return boolean 
     */
    public function getMainBrief()
    {
        return $this->main_brief;
    }

    /**
     * Set brief_translation
     *
     * @param integer $briefTranslation
     * @return Customfield
     */
    public function setBriefTranslation($briefTranslation)
    {
        $this->brief_translation = $briefTranslation;

        return $this;
    }

    /**
     * Get brief_translation
     *
     * @return integer 
     */
    public function getBriefTranslation()
    {
        return $this->brief_translation;
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
     * Set customwave
     *
     * @param \Albatross\CustomBundle\Entity\Customwave $customwave
     * @return Customfield
     */
    public function setCustomwave(\Albatross\CustomBundle\Entity\Customwave $customwave = null)
    {
        $this->customwave = $customwave;

        return $this;
    }

    /**
     * Get customwave
     *
     * @return \Albatross\CustomBundle\Entity\Customwave 
     */
    public function getCustomwave()
    {
        return $this->customwave;
    }

    /**
     * Set user
     *
     * @param \Albatross\UserBundle\Entity\User $user
     * @return Customfield
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



    ////////////////////////////////////////////////////////////////////////////
    private $file;
    private $file_2;
    private $file_3;
    private $file_4;

    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
    }
    public function setFile2(UploadedFile $file_2 = null) {
        $this->file_2 = $file_2;
    }
    public function setFile3(UploadedFile $file_3 = null) {
        $this->file_3 = $file_3;
    }
    public function setFile4(UploadedFile $file_4 = null) {
        $this->file_4 = $file_4;
    }

    public function getFile() {
        return $this->file;
    }
    public function getFile2() {
        return $this->file_2;
    }
    public function getFile3() {
        return $this->file_3;
    }
    public function getFile4() {
        return $this->file_4;
    }
    
    public function upload($type) {
        if (null === $this->getFile()) {
            return;
        } 
        $this->getFile()->move(
                $this->getUploadRootDir($type), $this->getFile()->getClientOriginalName()
        );
        if (null !== $this->getFile2()) {
            $this->getFile2()->move(
                    $this->getUploadRootDir($type), $this->getFile2()->getClientOriginalName());
            $this->path_2 = $this->getFile2()->getClientOriginalName();
        }
        if (null !== $this->getFile3()) {
            $this->getFile3()->move(
                    $this->getUploadRootDir($type), $this->getFile3()->getClientOriginalName());
            $this->path_3 = $this->getFile3()->getClientOriginalName();
        }
        if (null !== $this->getFile4()) {
            $this->getFile4()->move(
                    $this->getUploadRootDir($type), $this->getFile4()->getClientOriginalName());
            $this->path_4 = $this->getFile4()->getClientOriginalName();
        }
        
        $this->path = $this->getFile()->getClientOriginalName();
    }

    protected function getUploadDir($type) {
        $date = date('ymd');
        switch ($type){
            case 'report':
                return 'Report/'.$date;
                break;
            case 'brief':
                return 'Brief/'.$date;
                break;
            case 'material':
                return 'Material/'.$date;
                break;
            case 'questionnaire':
                return 'Questionnaire/'.$date.'/'.$this->getCustomwave();
                break;
        }
    }

    protected function getUploadRootDir($type) {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir($type);
    }

    public function getWebPath($type) {
        return null === $this->path ? null : $this->getUploadDir($type) . '/' . $this->path;
    }
    //for questionnaire
    public function getWebPath2($type) {
        var_dump($this->path_2);
        return null === $this->path_2 ? null : $this->getUploadDir($type) . '/' . $this->path_2;
    }
    public function getWebPath3($type) {
        return null === $this->path_3 ? null : $this->getUploadDir($type) . '/' . $this->path_3;
    }
    public function getWebPath4($type) {
        return null === $this->path_4 ? null : $this->getUploadDir($type) . '/' . $this->path_4;
    }
//
//    public function getAbsolutePath() {
//        return null === $this->file ? null : $this->getUploadRootDir() . '/' . $this->file;
//    }


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
    
        /**
     * Set country
     *
     * @param \Albatross\AceBundle\Entity\Country $country
     * @return Recap
     */
    public function setCountry(\Albatross\AceBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }
    /**
     * @var string
     */
    private $mm_brand;

    /**
     * @var string
     */
    private $mm_date;

    /**
     * @var string
     */
    private $mm_address;

    /**
     * @var string
     */
    private $mm_nextstep;

    /**
     * @var string
     */
    private $mm_agenda_of_the_meeting;

    /**
     * @var string
     */
    private $mm_clients_feedback;

    /**
     * @var string
     */
    private $mm_comments;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $attendees;


    /**
     * Set mm_brand
     *
     * @param string $mmBrand
     * @return Customfield
     */
    public function setMmBrand($mmBrand)
    {
        $this->mm_brand = $mmBrand;

        return $this;
    }

    /**
     * Get mm_brand
     *
     * @return string 
     */
    public function getMmBrand()
    {
        return $this->mm_brand;
    }

    /**
     * Set mm_date
     *
     * @param string $mmDate
     * @return Customfield
     */
    public function setMmDate($mmDate)
    {
        $this->mm_date = $mmDate;

        return $this;
    }

    /**
     * Get mm_date
     *
     * @return string 
     */
    public function getMmDate()
    {
        return $this->mm_date;
    }

    /**
     * Set mm_address
     *
     * @param string $mmAddress
     * @return Customfield
     */
    public function setMmAddress($mmAddress)
    {
        $this->mm_address = $mmAddress;

        return $this;
    }

    /**
     * Get mm_address
     *
     * @return string 
     */
    public function getMmAddress()
    {
        return $this->mm_address;
    }

    /**
     * Set mm_nextstep
     *
     * @param string $mmNextstep
     * @return Customfield
     */
    public function setMmNextstep($mmNextstep)
    {
        $this->mm_nextstep = $mmNextstep;

        return $this;
    }

    /**
     * Get mm_nextstep
     *
     * @return string 
     */
    public function getMmNextstep()
    {
        return $this->mm_nextstep;
    }

    /**
     * Set mm_agenda_of_the_meeting
     *
     * @param string $mmAgendaOfTheMeeting
     * @return Customfield
     */
    public function setMmAgendaOfTheMeeting($mmAgendaOfTheMeeting)
    {
        $this->mm_agenda_of_the_meeting = $mmAgendaOfTheMeeting;

        return $this;
    }

    /**
     * Get mm_agenda_of_the_meeting
     *
     * @return string 
     */
    public function getMmAgendaOfTheMeeting()
    {
        return $this->mm_agenda_of_the_meeting;
    }

    /**
     * Set mm_clients_feedback
     *
     * @param string $mmClientsFeedback
     * @return Customfield
     */
    public function setMmClientsFeedback($mmClientsFeedback)
    {
        $this->mm_clients_feedback = $mmClientsFeedback;

        return $this;
    }

    /**
     * Get mm_clients_feedback
     *
     * @return string 
     */
    public function getMmClientsFeedback()
    {
        return $this->mm_clients_feedback;
    }

    /**
     * Set mm_comments
     *
     * @param string $mmComments
     * @return Customfield
     */
    public function setMmComments($mmComments)
    {
        $this->mm_comments = $mmComments;

        return $this;
    }

    /**
     * Get mm_comments
     *
     * @return string 
     */
    public function getMmComments()
    {
        return $this->mm_comments;
    }

    /**
     * Add attendees
     *
     * @param \Albatross\CustomBundle\Entity\Attendees $attendees
     * @return Customfield
     */
    public function addAttendee(\Albatross\CustomBundle\Entity\Attendees $attendees)
    {
        $this->attendees[] = $attendees;

        return $this;
    }

    /**
     * Remove attendees
     *
     * @param \Albatross\CustomBundle\Entity\Attendees $attendees
     */
    public function removeAttendee(\Albatross\CustomBundle\Entity\Attendees $attendees)
    {
        $this->attendees->removeElement($attendees);
    }

    /**
     * Get attendees
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttendees()
    {
        return $this->attendees;
    }
    /**
     * @var string
     */
    private $mm_purpose;


    /**
     * Set mm_purpose
     *
     * @param string $mmPurpose
     * @return Customfield
     */
    public function setMmPurpose($mmPurpose)
    {
        $this->mm_purpose = $mmPurpose;

        return $this;
    }

    /**
     * Get mm_purpose
     *
     * @return string 
     */
    public function getMmPurpose()
    {
        return $this->mm_purpose;
    }
    
    public function __toString() {
        return $this->id;
    }
    /**
     * @var boolean
     */
    private $client_confirmation;

    /**
     * @var boolean
     */
    private $pm_confirmation;

    /**
     * @var string
     */
    private $client_signature;

    /**
     * @var string
     */
    private $pm_signature;


    /**
     * Set client_confirmation
     *
     * @param boolean $clientConfirmation
     * @return Customfield
     */
    public function setClientConfirmation($clientConfirmation)
    {
        $this->client_confirmation = $clientConfirmation;

        return $this;
    }

    /**
     * Get client_confirmation
     *
     * @return boolean 
     */
    public function getClientConfirmation()
    {
        return $this->client_confirmation;
    }

    /**
     * Set pm_confirmation
     *
     * @param boolean $pmConfirmation
     * @return Customfield
     */
    public function setPmConfirmation($pmConfirmation)
    {
        $this->pm_confirmation = $pmConfirmation;

        return $this;
    }

    /**
     * Get pm_confirmation
     *
     * @return boolean 
     */
    public function getPmConfirmation()
    {
        return $this->pm_confirmation;
    }

    /**
     * Set client_signature
     *
     * @param string $clientSignature
     * @return Customfield
     */
    public function setClientSignature($clientSignature)
    {
        $this->client_signature = $clientSignature;

        return $this;
    }

    /**
     * Get client_signature
     *
     * @return string 
     */
    public function getClientSignature()
    {
        return $this->client_signature;
    }

    /**
     * Set pm_signature
     *
     * @param string $pmSignature
     * @return Customfield
     */
    public function setPmSignature($pmSignature)
    {
        $this->pm_signature = $pmSignature;

        return $this;
    }

    /**
     * Get pm_signature
     *
     * @return string 
     */
    public function getPmSignature()
    {
        return $this->pm_signature;
    }
    /**
     * @var string
     */
    private $question_status;


    /**
     * Set question_status
     *
     * @param string $questionStatus
     * @return Customfield
     */
    public function setQuestionStatus($questionStatus)
    {
        $this->question_status = $questionStatus;

        return $this;
    }

    /**
     * Get question_status
     *
     * @return string 
     */
    public function getQuestionStatus()
    {
        return $this->question_status;
    }
    /**
     * @var boolean
     */
    private $upload_waiting_clonage;

    /**
     * @var boolean
     */
    private $proofreading;

    /**
     * @var string
     */
    private $upload_waiting_clonage_signature;

    /**
     * @var string
     */
    private $proofreading_signature;

    /**
     * @var boolean
     */
    private $choosen_type;


    /**
     * Set upload_waiting_clonage
     *
     * @param boolean $uploadWaitingClonage
     * @return Customfield
     */
    public function setUploadWaitingClonage($uploadWaitingClonage)
    {
        $this->upload_waiting_clonage = $uploadWaitingClonage;

        return $this;
    }

    /**
     * Get upload_waiting_clonage
     *
     * @return boolean 
     */
    public function getUploadWaitingClonage()
    {
        return $this->upload_waiting_clonage;
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
     * Set upload_waiting_clonage_signature
     *
     * @param string $uploadWaitingClonageSignature
     * @return Customfield
     */
    public function setUploadWaitingClonageSignature($uploadWaitingClonageSignature)
    {
        $this->upload_waiting_clonage_signature = $uploadWaitingClonageSignature;

        return $this;
    }

    /**
     * Get upload_waiting_clonage_signature
     *
     * @return string 
     */
    public function getUploadWaitingClonageSignature()
    {
        return $this->upload_waiting_clonage_signature;
    }

    /**
     * Set proofreading_signature
     *
     * @param string $proofreadingSignature
     * @return Customfield
     */
    public function setProofreadingSignature($proofreadingSignature)
    {
        $this->proofreading_signature = $proofreadingSignature;

        return $this;
    }

    /**
     * Get proofreading_signature
     *
     * @return string 
     */
    public function getProofreadingSignature()
    {
        return $this->proofreading_signature;
    }

    /**
     * Set choosen_type
     *
     * @param boolean $choosenType
     * @return Customfield
     */
    public function setChoosenType($choosenType)
    {
        $this->choosen_type = $choosenType;

        return $this;
    }

    /**
     * Get choosen_type
     *
     * @return boolean 
     */
    public function getChoosenType()
    {
        return $this->choosen_type;
    }
    /**
     * @var string
     */
    private $client_confirmation_time;


    /**
     * Set client_confirmation_time
     *
     * @param string $clientConfirmationTime
     * @return Customfield
     */
    public function setClientConfirmationTime($clientConfirmationTime)
    {
        $this->client_confirmation_time = $clientConfirmationTime;

        return $this;
    }

    /**
     * Get client_confirmation_time
     *
     * @return string 
     */
    public function getClientConfirmationTime()
    {
        return $this->client_confirmation_time;
    }
    /**
     * @var string
     */
    private $path_2;

    /**
     * @var string
     */
    private $path_3;

    /**
     * @var string
     */
    private $path_4;


    /**
     * Set path_2
     *
     * @param string $path2
     * @return Customfield
     */
    public function setPath2($path2)
    {
        $this->path_2 = $path2;

        return $this;
    }

    /**
     * Get path_2
     *
     * @return string 
     */
    public function getPath2()
    {
        return $this->path_2;
    }

    /**
     * Set path_3
     *
     * @param string $path3
     * @return Customfield
     */
    public function setPath3($path3)
    {
        $this->path_3 = $path3;

        return $this;
    }

    /**
     * Get path_3
     *
     * @return string 
     */
    public function getPath3()
    {
        return $this->path_3;
    }

    /**
     * Set path_4
     *
     * @param string $path4
     * @return Customfield
     */
    public function setPath4($path4)
    {
        $this->path_4 = $path4;

        return $this;
    }

    /**
     * Get path_4
     *
     * @return string 
     */
    public function getPath4()
    {
        return $this->path_4;
    }
    /**
     * @var string
     */
    private $question_file1_label;

    /**
     * @var string
     */
    private $question_file2_label;

    /**
     * @var string
     */
    private $question_file3_label;

    /**
     * @var string
     */
    private $question_file4_label;


    /**
     * Set question_file1_label
     *
     * @param string $questionFile1Label
     * @return Customfield
     */
    public function setQuestionFile1Label($questionFile1Label)
    {
        $this->question_file1_label = $questionFile1Label;

        return $this;
    }

    /**
     * Get question_file1_label
     *
     * @return string 
     */
    public function getQuestionFile1Label()
    {
        return $this->question_file1_label;
    }

    /**
     * Set question_file2_label
     *
     * @param string $questionFile2Label
     * @return Customfield
     */
    public function setQuestionFile2Label($questionFile2Label)
    {
        $this->question_file2_label = $questionFile2Label;

        return $this;
    }

    /**
     * Get question_file2_label
     *
     * @return string 
     */
    public function getQuestionFile2Label()
    {
        return $this->question_file2_label;
    }

    /**
     * Set question_file3_label
     *
     * @param string $questionFile3Label
     * @return Customfield
     */
    public function setQuestionFile3Label($questionFile3Label)
    {
        $this->question_file3_label = $questionFile3Label;

        return $this;
    }

    /**
     * Get question_file3_label
     *
     * @return string 
     */
    public function getQuestionFile3Label()
    {
        return $this->question_file3_label;
    }

    /**
     * Set question_file4_label
     *
     * @param string $questionFile4Label
     * @return Customfield
     */
    public function setQuestionFile4Label($questionFile4Label)
    {
        $this->question_file4_label = $questionFile4Label;

        return $this;
    }

    /**
     * Get question_file4_label
     *
     * @return string 
     */
    public function getQuestionFile4Label()
    {
        return $this->question_file4_label;
    }
    /**
     * @var boolean
     */
    private $quality_control;

    /**
     * @var boolean
     */
    private $testing;

    /**
     * @var string
     */
    private $quality_control_signature;

    /**
     * @var string
     */
    private $testing_signature;


    /**
     * Set quality_control
     *
     * @param boolean $qualityControl
     * @return Customfield
     */
    public function setQualityControl($qualityControl)
    {
        $this->quality_control = $qualityControl;

        return $this;
    }

    /**
     * Get quality_control
     *
     * @return boolean 
     */
    public function getQualityControl()
    {
        return $this->quality_control;
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
     * Set quality_control_signature
     *
     * @param string $qualityControlSignature
     * @return Customfield
     */
    public function setQualityControlSignature($qualityControlSignature)
    {
        $this->quality_control_signature = $qualityControlSignature;

        return $this;
    }

    /**
     * Get quality_control_signature
     *
     * @return string 
     */
    public function getQualityControlSignature()
    {
        return $this->quality_control_signature;
    }

    /**
     * Set testing_signature
     *
     * @param string $testingSignature
     * @return Customfield
     */
    public function setTestingSignature($testingSignature)
    {
        $this->testing_signature = $testingSignature;

        return $this;
    }

    /**
     * Get testing_signature
     *
     * @return string 
     */
    public function getTestingSignature()
    {
        return $this->testing_signature;
    }
    /**
     * @var string
     */
    private $question_end_time;


    /**
     * Set question_end_time
     *
     * @param string $questionEndTime
     * @return Customfield
     */
    public function setQuestionEndTime($questionEndTime)
    {
        $this->question_end_time = $questionEndTime;

        return $this;
    }

    /**
     * Get question_end_time
     *
     * @return string 
     */
    public function getQuestionEndTime()
    {
        return $this->question_end_time;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $aolquestionnaire;


    /**
     * Add aolquestionnaire
     *
     * @param \Albatross\CustomBundle\Entity\Aolquestionnaire $aolquestionnaire
     * @return Customfield
     */
    public function addAolquestionnaire(\Albatross\CustomBundle\Entity\Aolquestionnaire $aolquestionnaire)
    {
        $this->aolquestionnaire[] = $aolquestionnaire;

        return $this;
    }

    /**
     * Remove aolquestionnaire
     *
     * @param \Albatross\CustomBundle\Entity\Aolquestionnaire $aolquestionnaire
     */
    public function removeAolquestionnaire(\Albatross\CustomBundle\Entity\Aolquestionnaire $aolquestionnaire)
    {
        $this->aolquestionnaire->removeElement($aolquestionnaire);
    }

    /**
     * Get aolquestionnaire
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAolquestionnaire()
    {
        return $this->aolquestionnaire;
    }
}
