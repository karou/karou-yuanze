<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customclient
 *
 * @ORM\Table(name="customclient")
 * @ORM\Entity
 */
class Customclient
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\Clientgroup
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Clientgroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clientgroup_id", referencedColumnName="id")
     * })
     */
    private $clientgroup;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Operationquestionnaire", mappedBy="customclient")
     */
    private $operationquestionnaire;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->operationquestionnaire = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set name
     *
     * @param string $name
     * @return Customclient
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
     * Set logo
     *
     * @param string $logo
     * @return Customclient
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
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
     * Set clientgroup
     *
     * @param \Albatross\AceBundle\Entity\Clientgroup $clientgroup
     * @return Customclient
     */
    public function setClientgroup(\Albatross\AceBundle\Entity\Clientgroup $clientgroup = null)
    {
        $this->clientgroup = $clientgroup;

        return $this;
    }

    /**
     * Get clientgroup
     *
     * @return \Albatross\AceBundle\Entity\Clientgroup 
     */
    public function getClientgroup()
    {
        return $this->clientgroup;
    }

    /**
     * Add operationquestionnaire
     *
     * @param \Albatross\AceBundle\Entity\Operationquestionnaire $operationquestionnaire
     * @return Customclient
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
}
