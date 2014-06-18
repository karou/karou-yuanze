<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 */
class Country
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
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $bu;

    /**
     * @var \Albatross\AceBundle\Entity\Bu
     */
    private $Bu;


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
     * @return Country
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
     * Set code
     *
     * @param string $code
     * @return Country
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set bu
     *
     * @param string $bu
     * @return Country
     */
    public function setBu($bu)
    {
        $this->bu = $bu;

        return $this;
    }

    /**
     * Get bu
     *
     * @return string 
     */
    public function getBu()
    {
        return $this->bu;
    }
    /**
     * @var string
     */
    private $bu_code;


    /**
     * Set bu_code
     *
     * @param string $buCode
     * @return Country
     */
    public function setBuCode($buCode)
    {
        $this->bu_code = $buCode;

        return $this;
    }

    /**
     * Get bu_code
     *
     * @return string 
     */
    public function getBuCode()
    {
        return $this->bu_code;
    }
    public function __toString() {
        return $this->name;
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
     * @return Country
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
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $customfield;


    /**
     * Add customfield
     *
     * @param \Albatross\CustomBundle\Entity\Customfield $customfield
     * @return Country
     */
    public function addCustomfield(\Albatross\CustomBundle\Entity\Customfield $customfield)
    {
        $this->customfield[] = $customfield;

        return $this;
    }

    /**
     * Remove customfield
     *
     * @param \Albatross\CustomBundle\Entity\Customfield $customfield
     */
    public function removeCustomfield(\Albatross\CustomBundle\Entity\Customfield $customfield)
    {
        $this->customfield->removeElement($customfield);
    }

    /**
     * Get customfield
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomfield()
    {
        return $this->customfield;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $poslistdata;


    /**
     * Add poslistdata
     *
     * @param \Albatross\CustomBundle\Entity\Poslistdata $poslistdata
     * @return Country
     */
    public function addPoslistdatum(\Albatross\CustomBundle\Entity\Poslistdata $poslistdata)
    {
        $this->poslistdata[] = $poslistdata;

        return $this;
    }

    /**
     * Remove poslistdata
     *
     * @param \Albatross\CustomBundle\Entity\Poslistdata $poslistdata
     */
    public function removePoslistdatum(\Albatross\CustomBundle\Entity\Poslistdata $poslistdata)
    {
        $this->poslistdata->removeElement($poslistdata);
    }

    /**
     * Get poslistdata
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPoslistdata()
    {
        return $this->poslistdata;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $location;


    /**
     * Add location
     *
     * @param \Albatross\AceBundle\Entity\Location $location
     * @return Country
     */
    public function addLocation(\Albatross\AceBundle\Entity\Location $location)
    {
        $this->location[] = $location;

        return $this;
    }

    /**
     * Remove location
     *
     * @param \Albatross\AceBundle\Entity\Location $location
     */
    public function removeLocation(\Albatross\AceBundle\Entity\Location $location)
    {
        $this->location->removeElement($location);
    }

    /**
     * Get location
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLocation()
    {
        return $this->location;
    }
        /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $operationquestionnaire;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $operationproject;


    /**
     * Add operationquestionnaire
     *
     * @param \Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire
     * @return Country
     */
    public function addOperationquestionnaire(\Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire)
    {
        $this->operationquestionnaire[] = $operationquestionnaire;

        return $this;
    }

    /**
     * Remove operationquestionnaire
     *
     * @param \Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire
     */
    public function removeOperationquestionnaire(\Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire)
    {
        $this->operationquestionnaire->removeElement($operationquestionnaire);
    }

    /**
     * Get operationquestionnaire
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperationquestionnaire()
    {
        return $this->operationquestionnaire;
    }

    /**
     * Add operationproject
     *
     * @param \Albatross\OperationBundle\Entity\OperationProject $operationproject
     * @return Country
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
