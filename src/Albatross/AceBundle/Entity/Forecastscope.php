<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Forecastscope
 *
 * @ORM\Table(name="forecastscope")
 * @ORM\Entity
 */
class Forecastscope
{
    /**
     * @var string
     *
     * @ORM\Column(name="month", type="string", length=255, nullable=false)
     */
    private $month;

    /**
     * @var integer
     *
     * @ORM\Column(name="forecast", type="bigint", nullable=true)
     */
    private $forecast;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\Bu
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Bu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bu_id", referencedColumnName="id")
     * })
     */
    private $bu;



    /**
     * Set month
     *
     * @param string $month
     * @return Forecastscope
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return string 
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set forecast
     *
     * @param integer $forecast
     * @return Forecastscope
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
     * Set bu
     *
     * @param \Albatross\AceBundle\Entity\Bu $bu
     * @return Forecastscope
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
}
