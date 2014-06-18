<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Survey
 *
 * @ORM\Table(name="survey")
 * @ORM\Entity
 */
class Survey
{
    /**
     * @var integer
     *
     * @ORM\Column(name="client_id", type="bigint", nullable=true)
     */
    private $clientId;

    /**
     * @var string
     *
     * @ORM\Column(name="survey_name", type="string", length=255, nullable=false)
     */
    private $surveyName;

    /**
     * @var integer
     *
     * @ORM\Column(name="aol_id", type="bigint", nullable=false)
     */
    private $aolId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Rules", mappedBy="survey")
     */
    private $rules;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rules = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set clientId
     *
     * @param integer $clientId
     * @return Survey
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return integer 
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set surveyName
     *
     * @param string $surveyName
     * @return Survey
     */
    public function setSurveyName($surveyName)
    {
        $this->surveyName = $surveyName;

        return $this;
    }

    /**
     * Get surveyName
     *
     * @return string 
     */
    public function getSurveyName()
    {
        return $this->surveyName;
    }

    /**
     * Set aolId
     *
     * @param integer $aolId
     * @return Survey
     */
    public function setAolId($aolId)
    {
        $this->aolId = $aolId;

        return $this;
    }

    /**
     * Get aolId
     *
     * @return integer 
     */
    public function getAolId()
    {
        return $this->aolId;
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
     * Add rules
     *
     * @param \Albatross\AceBundle\Entity\Rules $rules
     * @return Survey
     */
    public function addRule(\Albatross\AceBundle\Entity\Rules $rules)
    {
        $this->rules[] = $rules;

        return $this;
    }

    /**
     * Remove rules
     *
     * @param \Albatross\AceBundle\Entity\Rules $rules
     */
    public function removeRule(\Albatross\AceBundle\Entity\Rules $rules)
    {
        $this->rules->removeElement($rules);
    }

    /**
     * Get rules
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRules()
    {
        return $this->rules;
    }
}
