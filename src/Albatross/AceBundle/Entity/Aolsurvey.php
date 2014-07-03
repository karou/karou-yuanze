<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aolsurvey
 */
class Aolsurvey
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $SurveyInstanceID;

    /**
     * @var integer
     */
    private $RFAsOpen;

    /**
     * @var integer
     */
    private $RFAsClosed;

    /**
     * @var boolean
     */
    private $isReturnedToShopper;

    /**
     * @var boolean
     */
    private $isNoDecline;

    /**
     * @var string
     */
    private $SurveyStatusName;

    /**
     * @var string
     */
    private $Date;

    /**
     * @var string
     */
    private $Client;

    /**
     * @var \DateTime
     */
    private $Submittime;

    /**
     * @var \Albatross\AceBundle\Entity\Billing
     */
    private $billing;

    /**
     * @var \Albatross\AceBundle\Entity\Campaign
     */
    private $campaign;

    /**
     * @var \Albatross\AceBundle\Entity\Workflow
     */
    private $workflow;

    /**
     * @var \Albatross\AceBundle\Entity\Location
     */
    private $location;


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
     * Set SurveyInstanceID
     *
     * @param integer $surveyInstanceID
     * @return Aolsurvey
     */
    public function setSurveyInstanceID($surveyInstanceID)
    {
        $this->SurveyInstanceID = $surveyInstanceID;

        return $this;
    }

    /**
     * Get SurveyInstanceID
     *
     * @return integer 
     */
    public function getSurveyInstanceID()
    {
        return $this->SurveyInstanceID;
    }

    /**
     * Set RFAsOpen
     *
     * @param integer $rFAsOpen
     * @return Aolsurvey
     */
    public function setRFAsOpen($rFAsOpen)
    {
        $this->RFAsOpen = $rFAsOpen;

        return $this;
    }

    /**
     * Get RFAsOpen
     *
     * @return integer 
     */
    public function getRFAsOpen()
    {
        return $this->RFAsOpen;
    }

    /**
     * Set RFAsClosed
     *
     * @param integer $rFAsClosed
     * @return Aolsurvey
     */
    public function setRFAsClosed($rFAsClosed)
    {
        $this->RFAsClosed = $rFAsClosed;

        return $this;
    }

    /**
     * Get RFAsClosed
     *
     * @return integer 
     */
    public function getRFAsClosed()
    {
        return $this->RFAsClosed;
    }

    /**
     * Set isReturnedToShopper
     *
     * @param boolean $isReturnedToShopper
     * @return Aolsurvey
     */
    public function setIsReturnedToShopper($isReturnedToShopper)
    {
        $this->isReturnedToShopper = $isReturnedToShopper;

        return $this;
    }

    /**
     * Get isReturnedToShopper
     *
     * @return boolean 
     */
    public function getIsReturnedToShopper()
    {
        return $this->isReturnedToShopper;
    }

    /**
     * Set isNoDecline
     *
     * @param boolean $isNoDecline
     * @return Aolsurvey
     */
    public function setIsNoDecline($isNoDecline)
    {
        $this->isNoDecline = $isNoDecline;

        return $this;
    }

    /**
     * Get isNoDecline
     *
     * @return boolean 
     */
    public function getIsNoDecline()
    {
        return $this->isNoDecline;
    }

    /**
     * Set SurveyStatusName
     *
     * @param string $surveyStatusName
     * @return Aolsurvey
     */
    public function setSurveyStatusName($surveyStatusName)
    {
        $this->SurveyStatusName = $surveyStatusName;

        return $this;
    }

    /**
     * Get SurveyStatusName
     *
     * @return string 
     */
    public function getSurveyStatusName()
    {
        return $this->SurveyStatusName;
    }

    /**
     * Set Date
     *
     * @param string $date
     * @return Aolsurvey
     */
    public function setDate($date)
    {
        $this->Date = $date;

        return $this;
    }

    /**
     * Get Date
     *
     * @return string 
     */
    public function getDate()
    {
        return $this->Date;
    }

    /**
     * Set Client
     *
     * @param string $client
     * @return Aolsurvey
     */
    public function setClient($client)
    {
        $this->Client = $client;

        return $this;
    }

    /**
     * Get Client
     *
     * @return string 
     */
    public function getClient()
    {
        return $this->Client;
    }

    /**
     * Set Submittime
     *
     * @param \DateTime $submittime
     * @return Aolsurvey
     */
    public function setSubmittime($submittime)
    {
        $this->Submittime = $submittime;

        return $this;
    }

    /**
     * Get Submittime
     *
     * @return \DateTime 
     */
    public function getSubmittime()
    {
        return $this->Submittime;
    }

    /**
     * Set billing
     *
     * @param \Albatross\AceBundle\Entity\Billing $billing
     * @return Aolsurvey
     */
    public function setBilling(\Albatross\AceBundle\Entity\Billing $billing = null)
    {
        $this->billing = $billing;

        return $this;
    }

    /**
     * Get billing
     *
     * @return \Albatross\AceBundle\Entity\Billing 
     */
    public function getBilling()
    {
        return $this->billing;
    }

    /**
     * Set campaign
     *
     * @param \Albatross\AceBundle\Entity\Campaign $campaign
     * @return Aolsurvey
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
     * Set workflow
     *
     * @param \Albatross\AceBundle\Entity\Workflow $workflow
     * @return Aolsurvey
     */
    public function setWorkflow(\Albatross\AceBundle\Entity\Workflow $workflow = null)
    {
        $this->workflow = $workflow;

        return $this;
    }

    /**
     * Get workflow
     *
     * @return \Albatross\AceBundle\Entity\Workflow 
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }

    /**
     * Set location
     *
     * @param \Albatross\AceBundle\Entity\Location $location
     * @return Aolsurvey
     */
    public function setLocation(\Albatross\AceBundle\Entity\Location $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \Albatross\AceBundle\Entity\Location 
     */
    public function getLocation()
    {
        return $this->location;
    }
    /**
     * @var string
     */
    private $MailboxName;


    /**
     * Set MailboxName
     *
     * @param string $mailboxName
     * @return Aolsurvey
     */
    public function setMailboxName($mailboxName)
    {
        $this->MailboxName = $mailboxName;

        return $this;
    }

    /**
     * Get MailboxName
     *
     * @return string 
     */
    public function getMailboxName()
    {
        return $this->MailboxName;
    }
    
    public function preRemove()
    {
        $this->getBilling()->setAolsurvey(null);
        
        return;
    }
}
