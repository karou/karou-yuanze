<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attachments
 */
class Attachments
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $status;

    /**
     * @var string
     */
    private $path;

    /**
     * @var \DateTime
     */
    private $submitteddate;

    /**
     * @var \Albatross\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Albatross\AceBundle\Entity\Project
     */
    private $project;

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
     * Set type
     *
     * @param string $type
     * @return Attachments
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set status
     *
     * @param array $status
     * @return Attachments
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return array 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Attachments
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
     * Set submitteddate
     *
     * @param \DateTime $submitteddate
     * @return Attachments
     */
    public function setSubmitteddate($submitteddate)
    {
        $this->submitteddate = $submitteddate;

        return $this;
    }

    /**
     * Get submitteddate
     *
     * @return \DateTime 
     */
    public function getSubmitteddate()
    {
        return $this->submitteddate;
    }

    /**
     * Set user
     *
     * @param \Albatross\UserBundle\Entity\User $user
     * @return Attachments
     */
    public function setUser(\Albatross\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Albatross\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set project
     *
     * @param \Albatross\AceBundle\Entity\Project $project
     * @return Attachments
     */
    public function setProject(\Albatross\AceBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Albatross\AceBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }
    /**
     * @var string
     */
    private $label;


    /**
     * Set label
     *
     * @param string $label
     * @return Attachments
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
     * @var string
     */
    private $message;


    /**
     * Set message
     *
     * @param string $message
     * @return Attachments
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $attachinfo;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attachinfo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add attachinfo
     *
     * @param \Albatross\AceBundle\Entity\Attachinfo $attachinfo
     * @return Attachments
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
     * @var \Albatross\AceBundle\Entity\Attachments
     */
    private $parent;


    /**
     * Set parent
     *
     * @param \Albatross\AceBundle\Entity\Attachments $parent
     * @return Attachments
     */
    public function setParent(\Albatross\AceBundle\Entity\Attachments $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Albatross\AceBundle\Entity\Attachments 
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * @var boolean
     */
    private $children;


    /**
     * Set children
     *
     * @param boolean $children
     * @return Attachments
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get children
     *
     * @return boolean 
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * @var \Albatross\CustomBundle\Entity\Customwave
     */
    private $customwave;


    /**
     * Set customwave
     *
     * @param \Albatross\CustomBundle\Entity\Customwave $customwave
     * @return Attachments
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
    
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return '';
    }
    
    public function __toString() {
        return $this->label;
    }
    /**
     * @var \Albatross\AceBundle\Entity\FileSection
     */
    private $filesection;


    /**
     * Set filesection
     *
     * @param \Albatross\AceBundle\Entity\FileSection $filesection
     * @return Attachments
     */
    public function setFilesection(\Albatross\AceBundle\Entity\FileSection $filesection = null)
    {
        $this->filesection = $filesection;

        return $this;
    }

    /**
     * Get filesection
     *
     * @return \Albatross\AceBundle\Entity\FileSection 
     */
    public function getFilesection()
    {
        return $this->filesection;
    }
    
    public function preRemove()
    {
        $this->setParent(null);
        
        return;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ioffile;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $iofmessage;


    /**
     * Add ioffile
     *
     * @param \Albatross\AceBundle\Entity\IOFFile $ioffile
     * @return Attachments
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
     * Add iofmessage
     *
     * @param \Albatross\AceBundle\Entity\IOFMessage $iofmessage
     * @return Attachments
     */
    public function addIofmessage(\Albatross\AceBundle\Entity\IOFMessage $iofmessage)
    {
        $this->iofmessage[] = $iofmessage;

        return $this;
    }

    /**
     * Remove iofmessage
     *
     * @param \Albatross\AceBundle\Entity\IOFMessage $iofmessage
     */
    public function removeIofmessage(\Albatross\AceBundle\Entity\IOFMessage $iofmessage)
    {
        $this->iofmessage->removeElement($iofmessage);
    }

    /**
     * Get iofmessage
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIofmessage()
    {
        return $this->iofmessage;
    }
    /**
     * @var string
     */
    private $submitby;


    /**
     * Set submitby
     *
     * @param string $submitby
     * @return Attachments
     */
    public function setSubmitby($submitby)
    {
        $this->submitby = $submitby;

        return $this;
    }

    /**
     * Get submitby
     *
     * @return string 
     */
    public function getSubmitby()
    {
        return $this->submitby;
    }
}
