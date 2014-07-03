<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IOFMessage
 */
class IOFMessage
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $message;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ioffile;

    /**
     * @var \Albatross\AceBundle\Entity\Attachments
     */
    private $attachments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ioffile = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set message
     *
     * @param string $message
     * @return IOFMessage
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Add ioffile
     *
     * @param \Albatross\AceBundle\Entity\IOFFile $ioffile
     * @return IOFMessage
     */
    public function addIoffile(\Albatross\AceBundle\Entity\IOFFile $ioffile)
    {
        $this->ioffile[] = $ioffile;

        return $this;
    }

    /**
     * Remove ioffile
     *
     * @param \Albatross\AceBundle\Entity\IOFFile $ioffile
     */
    public function removeIoffile(\Albatross\AceBundle\Entity\IOFFile $ioffile)
    {
        $this->ioffile->removeElement($ioffile);
    }

    /**
     * Get ioffile
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIoffile()
    {
        return $this->ioffile;
    }

    /**
     * Set attachments
     *
     * @param \Albatross\AceBundle\Entity\Attachments $attachments
     * @return IOFMessage
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $attachinfo;


    /**
     * Add attachinfo
     *
     * @param \Albatross\AceBundle\Entity\Attachinfo $attachinfo
     * @return IOFMessage
     */
    public function addAttachinfo(\Albatross\AceBundle\Entity\Attachinfo $attachinfo)
    {
        $this->attachinfo[] = $attachinfo;

        return $this;
    }

    /**
     * Remove attachinfo
     *
     * @param \Albatross\AceBundle\Entity\Attachinfo $attachinfo
     */
    public function removeAttachinfo(\Albatross\AceBundle\Entity\Attachinfo $attachinfo)
    {
        $this->attachinfo->removeElement($attachinfo);
    }

    /**
     * Get attachinfo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttachinfo()
    {
        return $this->attachinfo;
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
     * @return IOFMessage
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
     * @return IOFMessage
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
