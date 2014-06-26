<?php

namespace Albatross\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log
 */
class Log
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $number_page;

    /**
     * @var integer
     */
    private $number_action;

    /**
     * @var \DateTime
     */
    private $date_time;

    /**
     * @var \Albatross\AceBundle\Entity\Bu
     */
    private $bu;

    /**
     * @var \Albatross\UserBundle\Entity\User
     */
    private $user;


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
     * Set number_page
     *
     * @param integer $numberPage
     * @return Log
     */
    public function setNumberPage($numberPage)
    {
        $this->number_page = $numberPage;

        return $this;
    }

    /**
     * Get number_page
     *
     * @return integer 
     */
    public function getNumberPage()
    {
        return $this->number_page;
    }

    /**
     * Set number_action
     *
     * @param integer $numberAction
     * @return Log
     */
    public function setNumberAction($numberAction)
    {
        $this->number_action = $numberAction;

        return $this;
    }

    /**
     * Get number_action
     *
     * @return integer 
     */
    public function getNumberAction()
    {
        return $this->number_action;
    }

    /**
     * Set date_time
     *
     * @param \DateTime $dateTime
     * @return Log
     */
    public function setDateTime($dateTime)
    {
        $this->date_time = $dateTime;

        return $this;
    }

    /**
     * Get date_time
     *
     * @return \DateTime 
     */
    public function getDateTime()
    {
        return $this->date_time;
    }

    /**
     * Set bu
     *
     * @param \Albatross\AceBundle\Entity\Bu $bu
     * @return Log
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
     * Set user
     *
     * @param \Albatross\UserBundle\Entity\User $user
     * @return Log
     */
    public function setUser(\Albatross\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Albatross\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
