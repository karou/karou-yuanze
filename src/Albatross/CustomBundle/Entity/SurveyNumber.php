<?php

namespace Albatross\CustomBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SurveyNumber
 */
class SurveyNumber
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $pos;

    /**
     * @var integer
     */
    private $surveys;

    /**
     * @var integer
     */
    private $misfire;

    /**
     * @var integer
     */
    private $invalid;

    /**
     * @var string
     */
    private $scenarios;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \Albatross\CustomBundle\Entity\Recap
     */
    private $recap;


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
     * Set pos
     *
     * @param integer $pos
     * @return SurveyNumber
     */
    public function setPos($pos)
    {
        $this->pos = $pos;

        return $this;
    }

    /**
     * Get pos
     *
     * @return integer 
     */
    public function getPos()
    {
        return $this->pos;
    }

    /**
     * Set surveys
     *
     * @param integer $surveys
     * @return SurveyNumber
     */
    public function setSurveys($surveys)
    {
        $this->surveys = $surveys;

        return $this;
    }

    /**
     * Get surveys
     *
     * @return integer 
     */
    public function getSurveys()
    {
        return $this->surveys;
    }

    /**
     * Set misfire
     *
     * @param integer $misfire
     * @return SurveyNumber
     */
    public function setMisfire($misfire)
    {
        $this->misfire = $misfire;

        return $this;
    }

    /**
     * Get misfire
     *
     * @return integer 
     */
    public function getMisfire()
    {
        return $this->misfire;
    }

    /**
     * Set invalid
     *
     * @param integer $invalid
     * @return SurveyNumber
     */
    public function setInvalid($invalid)
    {
        $this->invalid = $invalid;

        return $this;
    }

    /**
     * Get invalid
     *
     * @return integer 
     */
    public function getInvalid()
    {
        return $this->invalid;
    }

    /**
     * Set scenarios
     *
     * @param string $scenarios
     * @return SurveyNumber
     */
    public function setScenarios($scenarios)
    {
        $this->scenarios = json_encode($scenarios);

        return $this;
    }

    /**
     * Get scenarios
     *
     * @return string 
     */
    public function getScenarios()
    {
        return $this->scenarios;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return SurveyNumber
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set recap
     *
     * @param \Albatross\CustomBundle\Entity\Recap $recap
     * @return SurveyNumber
     */
    public function setRecap(\Albatross\CustomBundle\Entity\Recap $recap = null)
    {
        $this->recap = $recap;

        return $this;
    }

    /**
     * Get recap
     *
     * @return \Albatross\CustomBundle\Entity\Recap 
     */
    public function getRecap()
    {
        return $this->recap;
    }
}
