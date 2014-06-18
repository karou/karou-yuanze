<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log
 *
 * @ORM\Table(name="log")
 * @ORM\Entity
 */
class Log
{
    /**
     * @var integer
     *
     * @ORM\Column(name="number_page", type="integer", nullable=true)
     */
    private $numberPage;

    /**
     * @var integer
     *
     * @ORM\Column(name="number_action", type="integer", nullable=true)
     */
    private $numberAction;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_time", type="datetime", nullable=true)
     */
    private $dateTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\Bu
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Bu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bu_id", referencedColumnName="id")
     * })
     */
    private $bu;

    /**
     * @var \Albatross\AceBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;



    /**
     * Set numberPage
     *
     * @param integer $numberPage
     * @return Log
     */
    public function setNumberPage($numberPage)
    {
        $this->numberPage = $numberPage;

        return $this;
    }

    /**
     * Get numberPage
     *
     * @return integer 
     */
    public function getNumberPage()
    {
        return $this->numberPage;
    }

    /**
     * Set numberAction
     *
     * @param integer $numberAction
     * @return Log
     */
    public function setNumberAction($numberAction)
    {
        $this->numberAction = $numberAction;

        return $this;
    }

    /**
     * Get numberAction
     *
     * @return integer 
     */
    public function getNumberAction()
    {
        return $this->numberAction;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     * @return Log
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get dateTime
     *
     * @return \DateTime 
     */
    public function getDateTime()
    {
        return $this->dateTime;
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
     * @param \Albatross\AceBundle\Entity\User $user
     * @return Log
     */
    public function setUser(\Albatross\AceBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Albatross\AceBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
