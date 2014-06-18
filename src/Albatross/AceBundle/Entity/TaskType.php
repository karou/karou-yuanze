<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskType
 *
 * @ORM\Table(name="task_type")
 * @ORM\Entity
 */
class TaskType
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="myorder", type="bigint", nullable=false)
     */
    private $myorder;

    /**
     * @var integer
     *
     * @ORM\Column(name="ace_id", type="bigint", nullable=false)
     */
    private $aceId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



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

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
