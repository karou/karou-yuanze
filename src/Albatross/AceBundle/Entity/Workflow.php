<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Workflow
 */
class Workflow
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $WorkflowStepID;

    /**
     * @var string
     */
    private $WorkflowStatus;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $aolsurveys;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->aolsurveys = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set WorkflowStepID
     *
     * @param integer $workflowStepID
     * @return Workflow
     */
    public function setWorkflowStepID($workflowStepID)
    {
        $this->WorkflowStepID = $workflowStepID;

        return $this;
    }

    /**
     * Get WorkflowStepID
     *
     * @return integer 
     */
    public function getWorkflowStepID()
    {
        return $this->WorkflowStepID;
    }

    /**
     * Set WorkflowStatus
     *
     * @param string $workflowStatus
     * @return Workflow
     */
    public function setWorkflowStatus($workflowStatus)
    {
        $this->WorkflowStatus = $workflowStatus;

        return $this;
    }

    /**
     * Get WorkflowStatus
     *
     * @return string 
     */
    public function getWorkflowStatus()
    {
        return $this->WorkflowStatus;
    }

    /**
     * Add aolsurveys
     *
     * @param \Albatross\AceBundle\Entity\Aolsurvey $aolsurveys
     * @return Workflow
     */
    public function addAolsurvey(\Albatross\AceBundle\Entity\Aolsurvey $aolsurveys)
    {
        $this->aolsurveys[] = $aolsurveys;

        return $this;
    }

    /**
     * Remove aolsurveys
     *
     * @param \Albatross\AceBundle\Entity\Aolsurvey $aolsurveys
     */
    public function removeAolsurvey(\Albatross\AceBundle\Entity\Aolsurvey $aolsurveys)
    {
        $this->aolsurveys->removeElement($aolsurveys);
    }

    /**
     * Get aolsurveys
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAolsurveys()
    {
        return $this->aolsurveys;
    }
}
