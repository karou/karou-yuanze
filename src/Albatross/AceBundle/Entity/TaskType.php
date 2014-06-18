<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskType
 */
class TaskType
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
    private $myorder;

    /**
     * @var integer
     */
    private $aceId;


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
     * @return TaskType
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
     * Set myorder
     *
     * @param integer $myorder
     * @return TaskType
     */
    public function setMyorder($myorder)
    {
        $this->myorder = $myorder;
    
        return $this;
    }

    /**
     * Get myorder
     *
     * @return integer 
     */
    public function getMyorder()
    {
        return $this->myorder;
    }

    /**
     * Set aceId
     *
     * @param integer $aceId
     * @return TaskType
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
}
