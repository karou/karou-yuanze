<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Questionnaire
 *
 * @ORM\Table(name="questionnaire")
 * @ORM\Entity
 */
class Questionnaire
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
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Albatross\AceBundle\Entity\Customwave", inversedBy="questionnaire")
     * @ORM\JoinTable(name="questionnaire_customwave",
     *   joinColumns={
     *     @ORM\JoinColumn(name="questionnaire_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="customwave_id", referencedColumnName="id")
     *   }
     * )
     */
    private $customwave;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customwave = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set name
     *
     * @param string $name
     * @return Questionnaire
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
     * Add customwave
     *
     * @param \Albatross\AceBundle\Entity\Customwave $customwave
     * @return Questionnaire
     */
    public function addCustomwave(\Albatross\AceBundle\Entity\Customwave $customwave)
    {
        $this->customwave[] = $customwave;

        return $this;
    }

    /**
     * Remove customwave
     *
     * @param \Albatross\AceBundle\Entity\Customwave $customwave
     */
    public function removeCustomwave(\Albatross\AceBundle\Entity\Customwave $customwave)
    {
        $this->customwave->removeElement($customwave);
    }

    /**
     * Get customwave
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomwave()
    {
        return $this->customwave;
    }
}
