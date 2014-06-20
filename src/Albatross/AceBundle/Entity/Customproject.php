<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customproject
 */
class Customproject
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
     * @var \Albatross\CustomBundle\Entity\Customproject
     */
    private $customproject;

    /**
     * @var \Albatross\CustomBundle\Entity\Customclient
     */
    private $customclient;


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
     * Set customclient
     *
     * @param \Albatross\CustomBundle\Entity\Customclient $customclient
     * @return Customproject
     */
    public function setCustomclient(\Albatross\CustomBundle\Entity\Customclient $customclient = null)
    {
        $this->customclient = $customclient;

        return $this;
    }

    /**
     * Get customclient
     *
     * @return \Albatross\CustomBundle\Entity\Customclient 
     */
    public function getCustomclient()
    {
        return $this->customclient;
    }
    /**
     * @var integer
     */
    private $scope;

    /**
     * @var integer
     */
    private $type;


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
     * @var \Albatross\CustomBundle\Entity\Customwave
     */
    private $customwave;


    /**
     * Set customwave
     *
     * @param \Albatross\CustomBundle\Entity\Customwave $customwave
     * @return Customproject
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

    public function __toString() {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customwave = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add customwave
     *
     * @param \Albatross\CustomBundle\Entity\Customwave $customwave
     * @return Customproject
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $user;


    /**
     * Add user
     *
     * @param \Albatross\UserBundle\Entity\User $user
     * @return Customproject
     */
    public function addUser(\Albatross\UserBundle\Entity\User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Albatross\UserBundle\Entity\User $user
     */
    public function removeUser(\Albatross\UserBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }
    
    private $reportDeliverySchedule;
    
    public function setReportDeliverySchedule($value) {
        $this->reportDeliverySchedule = $value;
    }
    
    public function getReportDeliverySchedule() {
        return $this->reportDeliverySchedule;
    }
}
