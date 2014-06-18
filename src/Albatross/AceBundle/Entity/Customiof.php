<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customiof
 *
 * @ORM\Table(name="customiof")
 * @ORM\Entity
 */
class Customiof
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
     * @var \Albatross\AceBundle\Entity\Customproject
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Customproject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customproject_id", referencedColumnName="id")
     * })
     */
    private $customproject;



    /**
     * Set name
     *
     * @param string $name
     * @return Customiof
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
     * Set customproject
     *
     * @param \Albatross\AceBundle\Entity\Customproject $customproject
     * @return Customiof
     */
    public function setCustomproject(\Albatross\AceBundle\Entity\Customproject $customproject = null)
    {
        $this->customproject = $customproject;

        return $this;
    }

    /**
     * Get customproject
     *
     * @return \Albatross\AceBundle\Entity\Customproject 
     */
    public function getCustomproject()
    {
        return $this->customproject;
    }
}
