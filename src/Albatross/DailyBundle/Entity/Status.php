<?php

namespace Albatross\DailyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Status
 */
class Status
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $status;


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
     * Set status
     *
     * @param string $status
     * @return Status
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @var boolean
     */
    private $editable;


    /**
     * Set editable
     *
     * @param boolean $editable
     * @return Status
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;

        return $this;
    }

    /**
     * Get editable
     *
     * @return boolean 
     */
    public function getEditable()
    {
        return $this->editable;
    }
    
    public function __toString() {
        return $this->status;
    }
    /**
     * @var integer
     */
    private $weight;


    /**
     * Set weight
     *
     * @param integer $weight
     * @return Status
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer 
     */
    public function getWeight()
    {
        return $this->weight;
    }
    /**
     * @var boolean
     */
    private $today;


    /**
     * Set today
     *
     * @param boolean $today
     * @return Status
     */
    public function setToday($today)
    {
        $this->today = $today;

        return $this;
    }

    /**
     * Get today
     *
     * @return boolean 
     */
    public function getToday()
    {
        return $this->today;
    }
}
