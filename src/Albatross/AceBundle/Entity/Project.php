<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 */
class Project
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
     * @var integer
     */
    private $number;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var \DateTime
     */
    private $createdDate;

    /**
     * @var float
     */
    private $percent;

    /**
     * @var integer
     */
    private $aceId;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tasks;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Project
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
     * Set number
     *
     * @param integer $number
     * @return Project
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
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Project
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    
        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Project
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    
        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return Project
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    
        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime 
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set percent
     *
     * @param float $percent
     * @return Project
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
    
        return $this;
    }

    /**
     * Get percent
     *
     * @return float 
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Set aceId
     *
     * @param integer $aceId
     * @return Project
     */
    public function setAceId($aceId)
    {
        $this->aceId = $aceId;
    
        return $this;
    }

    /**
     * Get aceId
     *
     * @return integer 
     */
    public function getAceId()
    {
        return $this->aceId;
    }

    /**
     * Add tasks
     *
     * @param \Albatross\AceBundle\Entity\Task $tasks
     * @return Project
     */
    public function addTask(\Albatross\AceBundle\Entity\Task $tasks)
    {
        $this->tasks[] = $tasks;
    
        return $this;
    }

    /**
     * Remove tasks
     *
     * @param \Albatross\AceBundle\Entity\Task $tasks
     */
    public function removeTask(\Albatross\AceBundle\Entity\Task $tasks)
    {
        $this->tasks->removeElement($tasks);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTasks()
    {
        return $this->tasks;
    }
    
    /**
     * @var string
     */
    private $description;


    /**
     * Set description
     *
     * @param string $description
     * @return Project
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $customwave;


    /**
     * Add customwave
     *
     * @param \Albatross\CustomBundle\Entity\Customwave $customwave
     * @return Project
     */
    public function addCustomwave(\Albatross\CustomBundle\Entity\Customwave $customwave)
    {
        $this->customwave[] = $customwave;

        return $this;
    }

    /**
     * Remove customwave
     *
     * @param \Albatross\CustomBundle\Entity\Customwave $customwave
     */
    public function removeCustomwave(\Albatross\CustomBundle\Entity\Customwave $customwave)
    {
        $this->customwave->removeElement($customwave);
    }

    /**
     * Get customwave
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomwave()
    {
        return $this->customwave;
    }

    /**
     * Set customwave
     *
     * @param \Albatross\CustomBundle\Entity\Customwave $customwave
     * @return Project
     */
    public function setCustomwave(\Albatross\CustomBundle\Entity\Customwave $customwave = null)
    {
        $this->customwave = $customwave;

        return $this;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $attachinfo;


    /**
     * Add attachinfo
     *
     * @param \Albatross\AceBundle\Entity\Attachinfo $attachinfo
     * @return Project
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $operationproject;


    /**
     * Add operationproject
     *
     * @param \Albatross\OperationBundle\Entity\OperationProject $operationproject
     * @return Project
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
}
