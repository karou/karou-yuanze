<?php

namespace Albatross\DailyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 */
class Client
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $clientName;


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
     * Set clientName
     *
     * @param string $clientName
     * @return Client
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * Get clientName
     *
     * @return string 
     */
    public function getClientName()
    {
        return $this->clientName;
    }
    /**
     * @var boolean
     */
    private $state;


    public function __toString() {
        return $this->clientName;
    }
    /**
     * @var integer
     */
    private $aolId;


    /**
     * Set aolId
     *
     * @param integer $aolId
     * @return Client
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
}
