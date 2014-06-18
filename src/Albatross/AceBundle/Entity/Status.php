<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Status
 *
 * @ORM\Table(name="status")
 * @ORM\Entity
 */
class Status
{
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="editable", type="boolean", nullable=false)
     */
    private $editable;

    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="bigint", nullable=true)
     */
    private $weight;

    /**
     * @var boolean
     *
     * @ORM\Column(name="today", type="boolean", nullable=false)
     */
    private $today;

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
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Rules", mappedBy="status")
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
     * @return Status
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
