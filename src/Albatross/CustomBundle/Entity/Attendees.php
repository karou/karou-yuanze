<?php

namespace Albatross\CustomBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attendees
 */
class Attendees
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
    private $position;

    /**
     * @var boolean
     */

    /**
     * @var \Albatross\CustomBundle\Entity\Customfield
     */
    private $customfield;


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
     * @return Attendees
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
     * Set position
     *
     * @param string $position
     * @return Attendees
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }
    /**
     * Set customfield
     *
     * @param \Albatross\CustomBundle\Entity\Customfield $customfield
     * @return Attendees
     */
    public function setCustomfield(\Albatross\CustomBundle\Entity\Customfield $customfield = null)
    {
        $this->customfield = $customfield;

        return $this;
    }

    /**
     * Get customfield
     *
     * @return \Albatross\CustomBundle\Entity\Customfield 
     */
    public function getCustomfield()
    {
        return $this->customfield;
    }
    /**
     * @var boolean
     */
    private $albatross_attendees;


    /**
     * Set albatross_attendees
     *
     * @param boolean $albatrossAttendees
     * @return Attendees
     */
    public function setAlbatrossAttendees($albatrossAttendees)
    {
        $this->albatross_attendees = $albatrossAttendees;

        return $this;
    }

    /**
     * Get albatross_attendees
     *
     * @return boolean 
     */
    public function getAlbatrossAttendees()
    {
        return $this->albatross_attendees;
    }
}
