<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bu
 *
 * @ORM\Table(name="bu")
 * @ORM\Entity
 */
class Bu
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    private $code;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="bigint", nullable=true)
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Operationquestionnaire", mappedBy="bu")
     */
    private $operationquestionnaire;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Operationproject", mappedBy="bu")
     */
    private $operationproject;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->operationquestionnaire = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operationproject = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set name
     *
     * @param string $name
     * @return Bu
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Bu
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return Bu
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
     * Add operationquestionnaire
     *
     * @param \Albatross\AceBundle\Entity\Operationquestionnaire $operationquestionnaire
     * @return Bu
     */
    public function addOperationquestionnaire(\Albatross\AceBundle\Entity\Operationquestionnaire $operationquestionnaire)
    {
        $this->operationquestionnaire[] = $operationquestionnaire;

        return $this;
    }

    /**
     * Remove operationquestionnaire
     *
     * @param \Albatross\AceBundle\Entity\Operationquestionnaire $operationquestionnaire
     */
    public function removeOperationquestionnaire(\Albatross\AceBundle\Entity\Operationquestionnaire $operationquestionnaire)
    {
        $this->operationquestionnaire->removeElement($operationquestionnaire);
    }

    /**
     * Get operationquestionnaire
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperationquestionnaire()
    {
        return $this->operationquestionnaire;
    }

    /**
     * Add operationproject
     *
     * @param \Albatross\AceBundle\Entity\Operationproject $operationproject
     * @return Bu
     */
    public function addOperationproject(\Albatross\AceBundle\Entity\Operationproject $operationproject)
    {
        $this->operationproject[] = $operationproject;

        return $this;
    }

    /**
     * Remove operationproject
     *
     * @param \Albatross\AceBundle\Entity\Operationproject $operationproject
     */
    public function removeOperationproject(\Albatross\AceBundle\Entity\Operationproject $operationproject)
    {
        $this->operationproject->removeElement($operationproject);
    }

    /**
     * Get operationproject
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperationproject()
    {
        return $this->operationproject;
    }
}
