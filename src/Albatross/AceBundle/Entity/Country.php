<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity
 */
class Country
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    private $code;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\Bu
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Bu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Bu_id", referencedColumnName="id")
     * })
     */
    private $bu;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Rules", mappedBy="country")
     */
    private $rules;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Recap", mappedBy="country")
     */
    private $recap;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Operationquestionnaire", mappedBy="country")
     */
    private $operationquestionnaire;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Operationproject", mappedBy="country")
     */
    private $operationproject;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Customfield", mappedBy="country")
     */
    private $customfield;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rules = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recap = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operationquestionnaire = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operationproject = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customfield = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set bu
     *
     * @param \Albatross\AceBundle\Entity\Bu $bu
     * @return Country
     */
    public function setBu(\Albatross\AceBundle\Entity\Bu $bu = null)
    {
        $this->bu = $bu;

        return $this;
    }

    /**
     * Get bu
     *
     * @return \Albatross\AceBundle\Entity\Bu 
     */
    public function getBu()
    {
        return $this->bu;
    }

    /**
     * Add rules
     *
     * @param \Albatross\AceBundle\Entity\Rules $rules
     * @return Country
     */
    public function addRule(\Albatross\AceBundle\Entity\Rules $rules)
    {
        $this->rules[] = $rules;

        return $this;
    }

    /**
     * Remove rules
     *
     * @param \Albatross\AceBundle\Entity\Rules $rules
     */
    public function removeRule(\Albatross\AceBundle\Entity\Rules $rules)
    {
        $this->rules->removeElement($rules);
    }

    /**
     * Get rules
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Add recap
     *
     * @param \Albatross\AceBundle\Entity\Recap $recap
     * @return Country
     */
    public function addRecap(\Albatross\AceBundle\Entity\Recap $recap)
    {
        $this->recap[] = $recap;

        return $this;
    }

    /**
     * Remove recap
     *
     * @param \Albatross\AceBundle\Entity\Recap $recap
     */
    public function removeRecap(\Albatross\AceBundle\Entity\Recap $recap)
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
     * Add operationquestionnaire
     *
     * @param \Albatross\AceBundle\Entity\Operationquestionnaire $operationquestionnaire
     * @return Country
     */
    public function addOperationquestionnaire(\Albatross\AceBundle\Entity\Operationquestionnaire $operationquestionnaire)
    {
        $this->operationquestionnaire[] = $operationquestionnaire;

        return $this;
    }

    /**
     * Remove operationquestionnaire
     *
     * @param \Albatross\AceBundle\Entity\Operationquestionnaire $operationquestionnaire
     */
    public function removeOperationquestionnaire(\Albatross\AceBundle\Entity\Operationquestionnaire $operationquestionnaire)
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
     * @param \Albatross\AceBundle\Entity\Operationproject $operationproject
     * @return Country
     */
    public function addOperationproject(\Albatross\AceBundle\Entity\Operationproject $operationproject)
    {
        $this->operationproject[] = $operationproject;

        return $this;
    }

    /**
     * Remove operationproject
     *
     * @param \Albatross\AceBundle\Entity\Operationproject $operationproject
     */
    public function removeOperationproject(\Albatross\AceBundle\Entity\Operationproject $operationproject)
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

    /**
     * Add customfield
     *
     * @param \Albatross\AceBundle\Entity\Customfield $customfield
     * @return Country
     */
    public function addCustomfield(\Albatross\AceBundle\Entity\Customfield $customfield)
    {
        $this->customfield[] = $customfield;

        return $this;
    }

    /**
     * Remove customfield
     *
     * @param \Albatross\AceBundle\Entity\Customfield $customfield
     */
    public function removeCustomfield(\Albatross\AceBundle\Entity\Customfield $customfield)
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
}
