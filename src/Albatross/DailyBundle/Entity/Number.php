<?php

namespace Albatross\DailyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Number
 */
class Number
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $number;

    /**
     * @var \Albatross\DailyBundle\Entity\Date
     */
    private $date;

    /**
     * @var \Albatross\DailyBundle\Entity\Status
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
     * Set number
     *
     * @param integer $number
     * @return Number
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set date
     *
     * @param \Albatross\DailyBundle\Entity\Date $date
     * @return Number
     */
    public function setDate(\Albatross\DailyBundle\Entity\Date $date = null)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \Albatross\DailyBundle\Entity\Date 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set status
     *
     * @param \Albatross\DailyBundle\Entity\Status $status
     * @return Number
     */
    public function setStatus(\Albatross\DailyBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Albatross\DailyBundle\Entity\Status 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
