<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IOFFile
 */
class IOFFile
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $path;

    /**
     * @var \Albatross\AceBundle\Entity\Attachments
     */
    private $attachments;

    /**
     * @var \Albatross\AceBundle\Entity\IOFMessage
     */
    private $iofmessage;


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
     * Set label
     *
     * @param string $label
     * @return IOFFile
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
     * @return IOFFile
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
     * Set attachments
     *
     * @param \Albatross\AceBundle\Entity\Attachments $attachments
     * @return IOFFile
     */
    public function setAttachments(\Albatross\AceBundle\Entity\Attachments $attachments = null)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * Get attachments
     *
     * @return \Albatross\AceBundle\Entity\Attachments 
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Set iofmessage
     *
     * @param \Albatross\AceBundle\Entity\IOFMessage $iofmessage
     * @return IOFFile
     */
    public function setIofmessage(\Albatross\AceBundle\Entity\IOFMessage $iofmessage = null)
    {
        $this->iofmessage = $iofmessage;

        return $this;
    }

    /**
     * Get iofmessage
     *
     * @return \Albatross\AceBundle\Entity\IOFMessage 
     */
    public function getIofmessage()
    {
        return $this->iofmessage;
    }

    
    /**
     * @var integer
     */
    private $formindex;

    /**
     * @var integer
     */
    private $formindex2;


    /**
     * Set formindex
     *
     * @param integer $formindex
     * @return IOFFile
     */
    public function setFormindex($formindex)
    {
        $this->formindex = $formindex;

        return $this;
    }

    /**
     * Get formindex
     *
     * @return integer 
     */
    public function getFormindex()
    {
        return $this->formindex;
    }

    /**
     * Set formindex2
     *
     * @param integer $formindex2
     * @return IOFFile
     */
    public function setFormindex2($formindex2)
    {
        $this->formindex2 = $formindex2;

        return $this;
    }

    /**
     * Get formindex2
     *
     * @return integer 
     */
    public function getFormindex2()
    {
        return $this->formindex2;
    }
}
