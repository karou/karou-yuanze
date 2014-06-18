<?php

namespace Albatross\CustomBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aolquestionnaire
 */
class Aolquestionnaire
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
     * @var \Albatross\CustomBundle\Entity\Customfield
     */
    private $customfield;


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
     * @return Aolquestionnaire
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
     * Set customfield
     *
     * @param \Albatross\CustomBundle\Entity\Customfield $customfield
     * @return Aolquestionnaire
     */
    public function setCustomfield(\Albatross\CustomBundle\Entity\Customfield $customfield = null)
    {
        $this->customfield = $customfield;

        return $this;
    }

    /**
     * Get customfield
     *
     * @return \Albatross\CustomBundle\Entity\Customfield 
     */
    public function getCustomfield()
    {
        return $this->customfield;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $recap;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recap = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add recap
     *
     * @param \Albatross\CustomBundle\Entity\Recap $recap
     * @return Aolquestionnaire
     */
    public function addRecap(\Albatross\CustomBundle\Entity\Recap $recap)
    {
        $this->recap[] = $recap;

        return $this;
    }

    /**
     * Remove recap
     *
     * @param \Albatross\CustomBundle\Entity\Recap $recap
     */
    public function removeRecap(\Albatross\CustomBundle\Entity\Recap $recap)
    {
        $this->recap->removeElement($recap);
    }

    /**
     * Get recap
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecap()
    {
        return $this->recap;
    }
}
