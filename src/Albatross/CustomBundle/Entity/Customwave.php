<?php

namespace Albatross\CustomBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * Customwave
 */
class Customwave {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Albatross\AceBundle\Entity\Attachments
     */
    private $attachments;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $recap;

    /**
     * @var \Albatross\CustomBundle\Entity\Customproject
     */
    private $customproject;

    /**
     * Constructor
     */
    public function __construct() {
        $this->recap = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Customwave
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set attachments
     *
     * @param \Albatross\AceBundle\Entity\Attachments $attachments
     * @return Customwave
     */
    public function setAttachments(\Albatross\AceBundle\Entity\Attachments $attachments = null) {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * Get attachments
     *
     * @return \Albatross\AceBundle\Entity\Attachments
     */
    public function getAttachments() {
        return $this->attachments;
    }

    /**
     * Add recap
     *
     * @param \Albatross\CustomBundle\Entity\Recap $recap
     * @return Customwave
     */
    public function addRecap(\Albatross\CustomBundle\Entity\Recap $recap) {
        $this->recap[] = $recap;

        return $this;
    }

    /**
     * Remove recap
     *
     * @param \Albatross\CustomBundle\Entity\Recap $recap
     */
    public function removeRecap(\Albatross\CustomBundle\Entity\Recap $recap) {
        $this->recap->removeElement($recap);
    }

    /**
     * Get recap
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecap() {
        return $this->recap;
    }

    /**
     * Set customproject
     *
     * @param \Albatross\CustomBundle\Entity\Customproject $customproject
     * @return Customwave
     */
    public function setCustomproject(\Albatross\CustomBundle\Entity\Customproject $customproject = null) {
        $this->customproject = $customproject;

        return $this;
    }

    /**
     * Get customproject
     *
     * @return \Albatross\CustomBundle\Entity\Customproject
     */
    public function getCustomproject() {
        return $this->customproject;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $project;

    /**
     * Add project
     *
     * @param \Albatross\AceBundle\Entity\Project $project
     * @return Customwave
     */
    public function addProject(\Albatross\AceBundle\Entity\Project $project) {
        $this->project[] = $project;

        return $this;
    }

    /**
     * Remove project
     *
     * @param \Albatross\AceBundle\Entity\Project $project
     */
    public function removeProject(\Albatross\AceBundle\Entity\Project $project) {
        $this->project->removeElement($project);
    }

    /**
     * Get project
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProject() {
        return $this->project;
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $customfield;

    /**
     * Add customfield
     *
     * @param \Albatross\CustomBundle\Entity\Customfield $customfield
     * @return Customwave
     */
    public function addCustomfield(\Albatross\CustomBundle\Entity\Customfield $customfield) {
        $this->customfield[] = $customfield;

        return $this;
    }

    /**
     * Remove customfield
     *
     * @param \Albatross\CustomBundle\Entity\Customfield $customfield
     */
    public function removeCustomfield(\Albatross\CustomBundle\Entity\Customfield $customfield) {
        $this->customfield->removeElement($customfield);
    }

    /**
     * Get customfield
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomfield() {
        return $this->customfield;
    }

    /**
     * @var \Albatross\CustomBundle\Entity\Poslist
     */
    private $poslist;

    /**
     * Set poslist
     *
     * @param \Albatross\CustomBundle\Entity\Poslist $poslist
     * @return Customwave
     */
    public function setPoslist(\Albatross\CustomBundle\Entity\Poslist $poslist = null) {
        $this->poslist = $poslist;

        return $this;
    }

    /**
     * Get poslist
     *
     * @return \Albatross\CustomBundle\Entity\Poslist
     */
    public function getPoslist() {
        return $this->poslist;
    }

    /**
     * @ORM\PreRemove
     * Release all the children on remove
     */
    public function preRemove() {
        foreach ($this->getProject() as $child)
            $child->setCustomwave(null);

        return;
    }

    /**
     * @var integer
     */
    private $wavenum;

    /**
     * Set wavenum
     *
     * @param integer $wavenum
     * @return Customwave
     */
    public function setWavenum($wavenum) {
        $this->wavenum = $wavenum;

        return $this;
    }

    /**
     * Get wavenum
     *
     * @return integer
     */
    public function getWavenum() {
        return $this->wavenum;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $aolsurvey;

    /**
     * Add aolsurveys
     *
     * @param \Albatross\AceBundle\Entity\Aolsurveys $aolsurveys
     * @return Customwave
     */
    public function addAolsurvey(\Albatross\AceBundle\Entity\Aolsurvey $aolsurvey) {
        $this->aolsurvey[] = $aolsurvey;

        return $this;
    }

    /**
     * Remove aolsurveys
     *
     * @param \Albatross\AceBundle\Entity\Aolsurveys $aolsurveys
     */
    public function removeAolsurvey(\Albatross\AceBundle\Entity\Aolsurvey $aolsurvey) {
        $this->aolsurvey->removeElement($aolsurvey);
    }

    /**
     * Get aolsurveys
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAolsurvey() {
        return $this->aolsurvey;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $questionnaire;

    /**
     * Add questionnaire
     *
     * @param \Albatross\AceBundle\Entity\Questionnaire $questionnaire
     * @return Customwave
     */
    public function addQuestionnaire(\Albatross\AceBundle\Entity\Questionnaire $questionnaire) {
        $this->questionnaire[] = $questionnaire;

        return $this;
    }

    /**
     * Remove questionnaire
     *
     * @param \Albatross\AceBundle\Entity\Questionnaire $questionnaire
     */
    public function removeQuestionnaire(\Albatross\AceBundle\Entity\Questionnaire $questionnaire) {
        $this->questionnaire->removeElement($questionnaire);
    }

    /**
     * Get questionnaire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestionnaire() {
        return $this->questionnaire;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $campaign;

    /**
     * Add campaign
     *
     * @param \Albatross\AceBundle\Entity\Campaign $campaign
     * @return Customwave
     */
    public function addCampaign(\Albatross\AceBundle\Entity\Campaign $campaign) {
        $this->campaign[] = $campaign;

        return $this;
    }

    /**
     * Remove campaign
     *
     * @param \Albatross\AceBundle\Entity\Campaign $campaign
     */
    public function removeCampaign(\Albatross\AceBundle\Entity\Campaign $campaign) {
        $this->campaign->removeElement($campaign);
    }

    /**
     * Get campaign
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCampaign() {
        return $this->campaign;
    }

    /**
     * @var string
     */
    private $bis;

    /**
     * Set bis
     *
     * @param string $bis
     * @return Customwave
     */
    public function setBis($bis) {
        $this->bis = $bis;

        return $this;
    }

    /**
     * Get bis
     *
     * @return string
     */
    public function getBis() {
        return $this->bis;
    }

    /**
     * @var string
     */
    private $year;

    /**
     * @var string
     */
    private $month;

    /**
     * Set year
     *
     * @param string $year
     * @return Customwave
     */
    public function setYear($year) {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear() {
        return $this->year;
    }

    /**
     * Set month
     *
     * @param string $month
     * @return Customwave
     */
    public function setMonth($month) {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return string
     */
    public function getMonth() {
        return $this->month;
    }

    /**
     * @var integer
     */
    private $totalnum;

    /**
     * Set totalnum
     *
     * @param integer $totalnum
     * @return Customwave
     */
    public function setTotalnum($totalnum) {
        $this->totalnum = $totalnum;

        return $this;
    }

    /**
     * Get totalnum
     *
     * @return integer
     */
    public function getTotalnum() {
        return $this->totalnum;
    }

    /**
     * @var \Albatross\CustomBundle\Entity\KickOffMeetingRecap
     */
    private $meeting_recap;

    /**
     * Set meeting_recap
     *
     * @param \Albatross\CustomBundle\Entity\KickOffMeetingRecap $meetingRecap
     * @return Customwave
     */
    public function setMeetingRecap(\Albatross\CustomBundle\Entity\KickOffMeetingRecap $meetingRecap = null) {
        $this->meeting_recap = $meetingRecap;

        return $this;
    }

    /**
     * Get meeting_recap
     *
     * @return \Albatross\CustomBundle\Entity\KickOffMeetingRecap
     */
    public function getMeetingRecap() {
        return $this->meeting_recap;
    }

    /**
     * @var \Albatross\UserBundle\Entity\User
     */
    private $user;

    /**
     * Set user
     *
     * @param \Albatross\UserBundle\Entity\User $user
     * @return Customwave
     */
    public function setUser(\Albatross\UserBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Albatross\UserBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @var boolean
     */
    private $last_start;

    /**
     * Set last_start
     *
     * @param boolean $lastStart
     * @return Customwave
     */
    public function setLastStart($lastStart) {
        $this->last_start = $lastStart;

        return $this;
    }

    /**
     * Get last_start
     *
     * @return boolean
     */
    public function getLastStart() {
        return $this->last_start;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $invoice;

    /**
     * Add invoice
     *
     * @param \Albatross\CustomBundle\Entity\Invoice $invoice
     * @return Customwave
     */
    public function addInvoice(\Albatross\CustomBundle\Entity\Invoice $invoice) {
        $this->invoice[] = $invoice;

        return $this;
    }

    /**
     * Remove invoice
     *
     * @param \Albatross\CustomBundle\Entity\Invoice $invoice
     */
    public function removeInvoice(\Albatross\CustomBundle\Entity\Invoice $invoice) {
        $this->invoice->removeElement($invoice);
    }

    /**
     * Get invoice
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoice() {
        return $this->invoice;
    }

    /**
     * @var \Albatross\UserBundle\Entity\User
     */
    private $project_manager;

    /**
     * Set project_manager
     *
     * @param \Albatross\UserBundle\Entity\User $projectManager
     * @return Customwave
     */
    public function setProjectManager(\Albatross\UserBundle\Entity\User $projectManager = null) {
        $this->project_manager = $projectManager;

        return $this;
    }

    /**
     * Get project_manager
     *
     * @return \Albatross\UserBundle\Entity\User
     */
    public function getProjectManager() {
        return $this->project_manager;
    }

    /**
     * @var \DateTime
     */
    private $delivery_date;

    /**
     * @var integer
     */
    private $fieldwork_percent;

    /**
     * @var integer
     */
    private $editing_percent;

    /**
     * Set delivery_date
     *
     * @param \DateTime $deliveryDate
     * @return Customwave
     */
    public function setDeliveryDate($deliveryDate) {
        $this->delivery_date = $deliveryDate;

        return $this;
    }

    /**
     * Get delivery_date
     *
     * @return \DateTime
     */
    public function getDeliveryDate() {
        return $this->delivery_date;
    }

    /**
     * Set fieldwork_percent
     *
     * @param integer $fieldworkPercent
     * @return Customwave
     */
    public function setFieldworkPercent($fieldworkPercent) {
        $this->fieldwork_percent = $fieldworkPercent;

        return $this;
    }

    /**
     * Get fieldwork_percent
     *
     * @return integer
     */
    public function getFieldworkPercent() {
        return $this->fieldwork_percent;
    }

    /**
     * Set editing_percent
     *
     * @param integer $editingPercent
     * @return Customwave
     */
    public function setEditingPercent($editingPercent) {
        $this->editing_percent = $editingPercent;

        return $this;
    }

    /**
     * Get editing_percent
     *
     * @return integer
     */
    public function getEditingPercent() {
        return $this->editing_percent;
    }

    /**
     * @var string
     */
    private $wave_step;

    /**
     * Set wave_step
     *
     * @param string $waveStep
     * @return Customwave
     */
    public function setWaveStep($waveStep) {
        $this->wave_step = $waveStep;

        return $this;
    }

    /**
     * Get wave_step
     *
     * @return string
     */
    public function getWaveStep() {
        return $this->wave_step;
    }

    /**
     * @var integer
     */
    private $assign_percent;

    /**
     * Set assign_percent
     *
     * @param integer $assignPercent
     * @return Customwave
     */
    public function setAssignPercent($assignPercent) {
        $this->assign_percent = $assignPercent;

        return $this;
    }

    /**
     * Get assign_percent
     *
     * @return integer
     */
    public function getAssignPercent() {
        return $this->assign_percent;
    }

}