<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity
 */
class Client
{
    /**
     * @var string
     *
     * @ORM\Column(name="client_name", type="string", length=255, nullable=false)
     */
    private $clientName;

    /**
     * @var integer
     *
     * @ORM\Column(name="aol_id", type="bigint", nullable=false)
     */
    private $aolId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Rules", mappedBy="client")
     */
    private $rules;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rules = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set clientName
     *
     * @param string $clientName
     * @return Client
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * Get clientName
     *
     * @return string 
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * Set aolId
     *
     * @param integer $aolId
     * @return Client
     */
    public function setAolId($aolId)
    {
        $this->aolId = $aolId;

        return $this;
    }

    /**
     * Get aolId
     *
     * @return integer 
     */
    public function getAolId()
    {
        return $this->aolId;
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
     * Add rules
     *
     * @param \Albatross\AceBundle\Entity\Rules $rules
     * @return Client
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
}
