<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customproject
 *
 * @ORM\Table(name="customproject")
 * @ORM\Entity
 */
class Customproject
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
     * @ORM\Column(name="scope", type="bigint", nullable=false)
     */
    private $scope;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="bigint", nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\Customclient
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Customclient")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customclient_id", referencedColumnName="id")
     * })
     */
    private $customclient;



    /**
     * Set name
     *
     * @param string $name
     * @return Customproject
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
     * Set scope
     *
     * @param integer $scope
     * @return Customproject
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return integer 
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Customproject
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
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
     * Set customclient
     *
     * @param \Albatross\AceBundle\Entity\Customclient $customclient
     * @return Customproject
     */
    public function setCustomclient(\Albatross\AceBundle\Entity\Customclient $customclient = null)
    {
        $this->customclient = $customclient;

        return $this;
    }

    /**
     * Get customclient
     *
     * @return \Albatross\AceBundle\Entity\Customclient 
     */
    public function getCustomclient()
    {
        return $this->customclient;
    }
}
