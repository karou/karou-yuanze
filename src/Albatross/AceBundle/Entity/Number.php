<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Number
 *
 * @ORM\Table(name="number")
 * @ORM\Entity
 */
class Number
{
    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="bigint", nullable=false)
     */
    private $number;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\Date
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Date")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="date_id", referencedColumnName="id")
     * })
     */
    private $date;

    /**
     * @var \Albatross\AceBundle\Entity\Status
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Status")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     * })
     */
    private $status;



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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \Albatross\AceBundle\Entity\Date $date
     * @return Number
     */
    public function setDate(\Albatross\AceBundle\Entity\Date $date = null)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \Albatross\AceBundle\Entity\Date 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set status
     *
     * @param \Albatross\AceBundle\Entity\Status $status
     * @return Number
     */
    public function setStatus(\Albatross\AceBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Albatross\AceBundle\Entity\Status 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
