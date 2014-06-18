<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ioffile
 *
 * @ORM\Table(name="ioffile")
 * @ORM\Entity
 */
class Ioffile
{
    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var integer
     *
     * @ORM\Column(name="formindex", type="bigint", nullable=false)
     */
    private $formindex;

    /**
     * @var string
     *
     * @ORM\Column(name="formindex2", type="string", length=20, nullable=false)
     */
    private $formindex2;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\Iofmessage
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Iofmessage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iofmessage_id", referencedColumnName="id")
     * })
     */
    private $iofmessage;

    /**
     * @var \Albatross\AceBundle\Entity\Attachments
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Attachments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attachments_id", referencedColumnName="id")
     * })
     */
    private $attachments;



    /**
     * Set label
     *
     * @param string $label
     * @return Ioffile
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Ioffile
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set formindex
     *
     * @param integer $formindex
     * @return Ioffile
     */
    public function setFormindex($formindex)
    {
        $this->formindex = $formindex;

        return $this;
    }

    /**
     * Get formindex
     *
     * @return integer 
     */
    public function getFormindex()
    {
        return $this->formindex;
    }

    /**
     * Set formindex2
     *
     * @param string $formindex2
     * @return Ioffile
     */
    public function setFormindex2($formindex2)
    {
        $this->formindex2 = $formindex2;

        return $this;
    }

    /**
     * Get formindex2
     *
     * @return string 
     */
    public function getFormindex2()
    {
        return $this->formindex2;
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
     * Set iofmessage
     *
     * @param \Albatross\AceBundle\Entity\Iofmessage $iofmessage
     * @return Ioffile
     */
    public function setIofmessage(\Albatross\AceBundle\Entity\Iofmessage $iofmessage = null)
    {
        $this->iofmessage = $iofmessage;

        return $this;
    }

    /**
     * Get iofmessage
     *
     * @return \Albatross\AceBundle\Entity\Iofmessage 
     */
    public function getIofmessage()
    {
        return $this->iofmessage;
    }

    /**
     * Set attachments
     *
     * @param \Albatross\AceBundle\Entity\Attachments $attachments
     * @return Ioffile
     */
    public function setAttachments(\Albatross\AceBundle\Entity\Attachments $attachments = null)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * Get attachments
     *
     * @return \Albatross\AceBundle\Entity\Attachments 
     */
    public function getAttachments()
    {
        return $this->attachments;
    }
}
