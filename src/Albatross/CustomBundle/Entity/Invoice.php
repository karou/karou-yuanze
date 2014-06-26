<?php

namespace Albatross\CustomBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Invoice
 */
class Invoice
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $invoice_type;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $number;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \Albatross\CustomBundle\Entity\Customwave
     */
    private $customwave;


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
     * Set invoice_type
     *
     * @param integer $invoiceType
     * @return Invoice
     */
    public function setInvoiceType($invoiceType)
    {
        $this->invoice_type = $invoiceType;

        return $this;
    }

    /**
     * Get invoice_type
     *
     * @return integer 
     */
    public function getInvoiceType()
    {
        return $this->invoice_type;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return Invoice
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Invoice
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return Invoice
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Invoice
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set customwave
     *
     * @param \Albatross\CustomBundle\Entity\Customwave $customwave
     * @return Invoice
     */
    public function setCustomwave(\Albatross\CustomBundle\Entity\Customwave $customwave = null)
    {
        $this->customwave = $customwave;

        return $this;
    }

    /**
     * Get customwave
     *
     * @return \Albatross\CustomBundle\Entity\Customwave 
     */
    public function getCustomwave()
    {
        return $this->customwave;
    }
    
    private $file;
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
    }
    public function getFile() {
        return $this->file;
    }
    /**
     * @var \Albatross\UserBundle\Entity\User
     */
    private $project_manager;

    /**
     * @var \Albatross\AceBundle\Entity\Bu
     */
    private $bu;


    /**
     * Set project_manager
     *
     * @param \Albatross\UserBundle\Entity\User $projectManager
     * @return Invoice
     */
    public function setProjectManager(\Albatross\UserBundle\Entity\User $projectManager = null)
    {
        $this->project_manager = $projectManager;

        return $this;
    }

    /**
     * Get project_manager
     *
     * @return \Albatross\UserBundle\Entity\User 
     */
    public function getProjectManager()
    {
        return $this->project_manager;
    }

    /**
     * Set bu
     *
     * @param \Albatross\AceBundle\Entity\Bu $bu
     * @return Invoice
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
     * @var boolean
     */
    private $regional;


    /**
     * Set regional
     *
     * @param boolean $regional
     * @return Invoice
     */
    public function setRegional($regional)
    {
        $this->regional = $regional;

        return $this;
    }

    /**
     * Get regional
     *
     * @return boolean 
     */
    public function getRegional()
    {
        return $this->regional;
    }
}
