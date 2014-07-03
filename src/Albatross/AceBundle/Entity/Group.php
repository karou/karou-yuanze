<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Group
 */
class Group
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
     * @return Group
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
     * @return Group
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
     * @return Group
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
