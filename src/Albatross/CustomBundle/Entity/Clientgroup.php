<?php

namespace Albatross\CustomBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clientgroup
 */
class Clientgroup
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
    private $customclient;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customclient = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Clientgroup
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
     * Add customclient
     *
     * @param \Albatross\CustomBundle\Entity\Customclient $customclient
     * @return Clientgroup
     */
    public function addCustomclient(\Albatross\CustomBundle\Entity\Customclient $customclient)
    {
        $this->customclient[] = $customclient;

        return $this;
    }

    /**
     * Remove customclient
     *
     * @param \Albatross\CustomBundle\Entity\Customclient $customclient
     */
    public function removeCustomclient(\Albatross\CustomBundle\Entity\Customclient $customclient)
    {
        $this->customclient->removeElement($customclient);
    }

    /**
     * Get customclient
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomclient()
    {
        return $this->customclient;
    }
}
