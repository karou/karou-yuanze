<?php

namespace Albatross\DailyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Survey
 */
class Survey {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $surveyName;

    /**
     * @var integer
     */
    private $aolId;

    /**
     * @var \Albatross\DailyBundle\Entity\Client
     */
    private $client;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set surveyName
     *
     * @param string $surveyName
     * @return Survey
     */
    public function setSurveyName($surveyName) {
        $this->surveyName = $surveyName;

        return $this;
    }

    /**
     * Get surveyName
     *
     * @return string 
     */
    public function getSurveyName() {
        return $this->surveyName;
    }

    /**
     * Set aolId
     *
     * @param integer $aolId
     * @return Survey
     */
    public function setAolId($aolId) {
        $this->aolId = $aolId;

        return $this;
    }

    /**
     * Get aolId
     *
     * @return integer 
     */
    public function getAolId() {
        return $this->aolId;
    }

    /**
     * Set client
     *
     * @param \Albatross\DailyBundle\Entity\Client $client
     * @return Survey
     */
    public function setClient(\Albatross\DailyBundle\Entity\Client $client = null) {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Albatross\DailyBundle\Entity\Client 
     */
    public function getClient() {
        return $this->client;
    }

    public function __toString() {
        return $this->surveyName;
    }

}
