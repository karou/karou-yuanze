<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aolquestionnaire
 *
 * @ORM\Table(name="aolquestionnaire")
 * @ORM\Entity
 */
class Aolquestionnaire
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\Customfield
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Customfield")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customfield_id", referencedColumnName="id")
     * })
     */
    private $customfield;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Recap", inversedBy="aolquestionnaire")
     * @ORM\JoinTable(name="aolquestionnaire_recap",
     *   joinColumns={
     *     @ORM\JoinColumn(name="aolquestionnaire_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="recap_id", referencedColumnName="id")
     *   }
     * )
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set customfield
     *
     * @param \Albatross\AceBundle\Entity\Customfield $customfield
     * @return Aolquestionnaire
     */
    public function setCustomfield(\Albatross\AceBundle\Entity\Customfield $customfield = null)
    {
        $this->customfield = $customfield;

        return $this;
    }

    /**
     * Get customfield
     *
     * @return \Albatross\AceBundle\Entity\Customfield 
     */
    public function getCustomfield()
    {
        return $this->customfield;
    }

    /**
     * Add recap
     *
     * @param \Albatross\AceBundle\Entity\Recap $recap
     * @return Aolquestionnaire
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
}
