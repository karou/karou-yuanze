<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Workflow
 *
 * @ORM\Table(name="workflow")
 * @ORM\Entity
 */
class Workflow
{
    /**
     * @var integer
     *
     * @ORM\Column(name="workflow_step_id", type="bigint", nullable=false)
     */
    private $workflowStepId;

    /**
     * @var string
     *
     * @ORM\Column(name="workflow_status", type="string", length=255, nullable=false)
     */
    private $workflowStatus;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set workflowStepId
     *
     * @param integer $workflowStepId
     * @return Workflow
     */
    public function setWorkflowStepId($workflowStepId)
    {
        $this->workflowStepId = $workflowStepId;

        return $this;
    }

    /**
     * Get workflowStepId
     *
     * @return integer 
     */
    public function getWorkflowStepId()
    {
        return $this->workflowStepId;
    }

    /**
     * Set workflowStatus
     *
     * @param string $workflowStatus
     * @return Workflow
     */
    public function setWorkflowStatus($workflowStatus)
    {
        $this->workflowStatus = $workflowStatus;

        return $this;
    }

    /**
     * Get workflowStatus
     *
     * @return string 
     */
    public function getWorkflowStatus()
    {
        return $this->workflowStatus;
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
}
