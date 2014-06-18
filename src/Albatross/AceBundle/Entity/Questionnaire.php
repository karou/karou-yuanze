<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Questionnaire
 */
class Questionnaire
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $campaign;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->campaign = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Questionnaire
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add campaign
     *
     * @param \Albatross\AceBundle\Entity\Campaign $campaign
     * @return Questionnaire
     */
    public function addCampaign(\Albatross\AceBundle\Entity\Campaign $campaign)
    {
        $this->campaign[] = $campaign;

        return $this;
    }

    /**
     * Remove campaign
     *
     * @param \Albatross\AceBundle\Entity\Campaign $campaign
     */
    public function removeCampaign(\Albatross\AceBundle\Entity\Campaign $campaign)
    {
        $this->campaign->removeElement($campaign);
    }

    /**
     * Get campaign
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCampaign()
    {
        return $this->campaign;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $customwave;


    /**
     * Add customwave
     *
     * @param \Albatross\CustomBundle\Entity\Customwave $customwave
     * @return Questionnaire
     */
    public function addCustomwave(\Albatross\CustomBundle\Entity\Customwave $customwave)
    {
        $this->customwave[] = $customwave;

        return $this;
    }

    /**
     * Remove customwave
     *
     * @param \Albatross\CustomBundle\Entity\Customwave $customwave
     */
    public function removeCustomwave(\Albatross\CustomBundle\Entity\Customwave $customwave)
    {
        $this->customwave->removeElement($customwave);
    }

    /**
     * Get customwave
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomwave()
    {
        return $this->customwave;
    }
        /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $operationquestionnaire;


    /**
     * Add operationquestionnaire
     *
     * @param \Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire
     * @return Questionnaire
     */
    public function addOperationquestionnaire(\Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire)
    {
        $this->operationquestionnaire[] = $operationquestionnaire;

        return $this;
    }

    /**
     * Remove operationquestionnaire
     *
     * @param \Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire
     */
    public function removeOperationquestionnaire(\Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire)
    {
        $this->operationquestionnaire->removeElement($operationquestionnaire);
    }

    /**
     * Get operationquestionnaire
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperationquestionnaire()
    {
        return $this->operationquestionnaire;
    }
}
