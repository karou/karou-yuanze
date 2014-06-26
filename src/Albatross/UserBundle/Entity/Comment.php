<?php

namespace Albatross\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 */
class Comment
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTime
     */
    private $submittime;

    /**
     * @var \Albatross\CustomBundle\Entity\Customwave
     */
    private $wave;

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
     * Set content
     *
     * @param string $content
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set submittime
     *
     * @param \DateTime $submittime
     * @return Comment
     */
    public function setSubmittime($submittime)
    {
        $this->submittime = $submittime;
    
        return $this;
    }

    /**
     * Get submittime
     *
     * @return \DateTime 
     */
    public function getSubmittime()
    {
        return $this->submittime;
    }

    /**
     * Set wave
     *
     * @param \Albatross\CustomBundle\Entity\Customwave $wave
     * @return Comment
     */
    public function setWave(\Albatross\CustomBundle\Entity\Customwave $wave = null)
    {
        $this->wave = $wave;
    
        return $this;
    }

    /**
     * Get wave
     *
     * @return \Albatross\CustomBundle\Entity\Customwave 
     */
    public function getWave()
    {
        return $this->wave;
    }

    /**
     * Set user
     *
     * @param \Albatross\UserBundle\Entity\User $user
     * @return Comment
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
