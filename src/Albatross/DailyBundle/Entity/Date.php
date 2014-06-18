<?php

namespace Albatross\DailyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Date
 */
class Date
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $dailydate;


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
     * Set dailydate
     *
     * @param \DateTime $dailydate
     * @return Date
     */
    public function setDailydate($dailydate)
    {
        $this->dailydate = $dailydate;

        return $this;
    }

    /**
     * Get dailydate
     *
     * @return \DateTime 
     */
    public function getDailydate()
    {
        return $this->dailydate;
    }
    /**
     * @var \Albatross\AceBundle\Entity\Bu
     */
    private $bu;


    /**
     * Set bu
     *
     * @param \Albatross\AceBundle\Entity\Bu $bu
     * @return Date
     */
    public function setBu(\Albatross\AceBundle\Entity\Bu $bu = null)
    {
        $this->bu = $bu;

        return $this;
    }

    /**
     * Get bu
     *
     * @return \Albatross\AceBundle\Entity\Bu 
     */
    public function getBu()
    {
        return $this->bu;
    }
    /**
     * @var integer
     */
    private $forecast;


    /**
     * Set forecast
     *
     * @param integer $forecast
     * @return Date
     */
    public function setForecast($forecast)
    {
        $this->forecast = $forecast;

        return $this;
    }

    /**
     * Get forecast
     *
     * @return integer 
     */
    public function getForecast()
    {
        return $this->forecast;
    }
}
