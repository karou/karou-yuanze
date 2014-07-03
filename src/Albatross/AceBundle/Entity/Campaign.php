<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Campaign
 */
class Campaign
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
    private $aolsurvey;

    /**
     * @var \Albatross\AceBundle\Entity\Questionnaire
     */
    private $questionnaire;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->aolsurvey = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Campaign
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
     * Add aolsurvey
     *
     * @param \Albatross\AceBundle\Entity\Aolsurvey $aolsurvey
     * @return Campaign
     */
    public function addAolsurvey(\Albatross\AceBundle\Entity\Aolsurvey $aolsurvey)
    {
        $this->aolsurvey[] = $aolsurvey;

        return $this;
    }

    /**
     * Remove aolsurvey
     *
     * @param \Albatross\AceBundle\Entity\Aolsurvey $aolsurvey
     */
    public function removeAolsurvey(\Albatross\AceBundle\Entity\Aolsurvey $aolsurvey)
    {
        $this->aolsurvey->removeElement($aolsurvey);
    }

    /**
     * Get aolsurvey
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAolsurvey()
    {
        return $this->aolsurvey;
    }

    /**
     * Set questionnaire
     *
     * @param \Albatross\AceBundle\Entity\Questionnaire $questionnaire
     * @return Campaign
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $customwave;


    /**
     * Add customwave
     *
     * @param \Albatross\CustomBundle\Entity\Customwave $customwave
     * @return Campaign
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
     * @var \Albatross\OperationBundle\Entity\OperationQuestionnaire
     */
    private $operationquestionnaire;


    /**
     * Set operationquestionnaire
     *
     * @param \Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire
     * @return Campaign
     */
    public function setOperationquestionnaire(\Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire = null)
    {
        $this->operationquestionnaire = $operationquestionnaire;

        return $this;
    }

    /**
     * Get operationquestionnaire
     *
     * @return \Albatross\OperationBundle\Entity\OperationQuestionnaire 
     */
    public function getOperationquestionnaire()
    {
        return $this->operationquestionnaire;
    }
}
