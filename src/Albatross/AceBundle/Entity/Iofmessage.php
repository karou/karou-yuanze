<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Iofmessage
 *
 * @ORM\Table(name="iofmessage")
 * @ORM\Entity
 */
class Iofmessage
{
    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="formindex", type="string", length=20, nullable=false)
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
     * @var \Albatross\AceBundle\Entity\Attachments
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Attachments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attachments_id", referencedColumnName="id")
     * })
     */
    private $attachments;



    /**
     * Set message
     *
     * @param string $message
     * @return Iofmessage
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set formindex
     *
     * @param string $formindex
     * @return Iofmessage
     */
    public function setFormindex($formindex)
    {
        $this->formindex = $formindex;

        return $this;
    }

    /**
     * Get formindex
     *
     * @return string 
     */
    public function getFormindex()
    {
        return $this->formindex;
    }

    /**
     * Set formindex2
     *
     * @param string $formindex2
     * @return Iofmessage
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
     * Set attachments
     *
     * @param \Albatross\AceBundle\Entity\Attachments $attachments
     * @return Iofmessage
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
