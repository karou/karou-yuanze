<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FileSection
 */
class FileSection
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $attachments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attachments = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return FileSection
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
     * Add attachments
     *
     * @param \Albatross\AceBundle\Entity\Attachments $attachments
     * @return FileSection
     */
    public function addAttachment(\Albatross\AceBundle\Entity\Attachments $attachments)
    {
        $this->attachments[] = $attachments;

        return $this;
    }

    /**
     * Remove attachments
     *
     * @param \Albatross\AceBundle\Entity\Attachments $attachments
     */
    public function removeAttachment(\Albatross\AceBundle\Entity\Attachments $attachments)
    {
        $this->attachments->removeElement($attachments);
    }

    /**
     * Get attachments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttachments()
    {
        return $this->attachments;
    }
    /**
     * @var string
     */
    private $description;


    /**
     * Set description
     *
     * @param string $description
     * @return FileSection
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
     * @var \Albatross\AceBundle\Entity\FileSection
     */
    private $parent;


    /**
     * Set parent
     *
     * @param \Albatross\AceBundle\Entity\FileSection $parent
     * @return FileSection
     */
    public function setParent(\Albatross\AceBundle\Entity\FileSection $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Albatross\AceBundle\Entity\FileSection 
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    public function getLastUploadedAttachment($type = 3) {
        $res = null;
        foreach ($this->attachments as $attachment) {
            if ($attachment->getType() == $type)
                $res = $attachment;
        }
        return $res;
    }
}
