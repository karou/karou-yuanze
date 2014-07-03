<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bu
 */
class Bu
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $code;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $country;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->country = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add country
     *
     * @param \Albatross\AceBundle\Entity\country $country
     * @return Bu
     */
    public function addCountry(\Albatross\AceBundle\Entity\country $country)
    {
        $this->country[] = $country;

        return $this;
    }

    /**
     * Remove country
     *
     * @param \Albatross\AceBundle\Entity\country $country
     */
    public function removeCountry(\Albatross\AceBundle\Entity\country $country)
    {
        $this->country->removeElement($country);
    }

    /**
     * Get country
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCountry()
    {
        return $this->country;
    }
    
    public function __toString() {
        return $this->code;
    }
    /**
     * @var integer
     */
    private $number;


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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $operationquestionnaire;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $operationproject;


    /**
     * Add operationquestionnaire
     *
     * @param \Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire
     * @return Bu
     */
    public function addOperationquestionnaire(\Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire)
    {
        $this->operationquestionnaire[] = $operationquestionnaire;

        return $this;
    }

    /**
     * Remove operationquestionnaire
     *
     * @param \Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire
     */
    public function removeOperationquestionnaire(\Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire)
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
     * @param \Albatross\OperationBundle\Entity\OperationProject $operationproject
     * @return Bu
     */
    public function addOperationproject(\Albatross\OperationBundle\Entity\OperationProject $operationproject)
    {
        $this->operationproject[] = $operationproject;

        return $this;
    }

    /**
     * Remove operationproject
     *
     * @param \Albatross\OperationBundle\Entity\OperationProject $operationproject
     */
    public function removeOperationproject(\Albatross\OperationBundle\Entity\OperationProject $operationproject)
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
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $log;


    /**
     * Add log
     *
     * @param \Albatross\UserBundle\Entity\Log $log
     * @return Bu
     */
    public function addLog(\Albatross\UserBundle\Entity\Log $log)
    {
        $this->log[] = $log;

        return $this;
    }

    /**
     * Remove log
     *
     * @param \Albatross\UserBundle\Entity\Log $log
     */
    public function removeLog(\Albatross\UserBundle\Entity\Log $log)
    {
        $this->log->removeElement($log);
    }

    /**
     * Get log
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLog()
    {
        return $this->log;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $invoice;


    /**
     * Add invoice
     *
     * @param \Albatross\CustomBundle\Entity\Invoice $invoice
     * @return Bu
     */
    public function addInvoice(\Albatross\CustomBundle\Entity\Invoice $invoice)
    {
        $this->invoice[] = $invoice;

        return $this;
    }

    /**
     * Remove invoice
     *
     * @param \Albatross\CustomBundle\Entity\Invoice $invoice
     */
    public function removeInvoice(\Albatross\CustomBundle\Entity\Invoice $invoice)
    {
        $this->invoice->removeElement($invoice);
    }

    /**
     * Get invoice
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInvoice()
    {
        return $this->invoice;
    }
}
