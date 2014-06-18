<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserCustomclient
 *
 * @ORM\Table(name="user_customclient")
 * @ORM\Entity
 */
class UserCustomclient
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="customclient_id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $customclientId;



    /**
     * Set userId
     *
     * @param integer $userId
     * @return UserCustomclient
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set customclientId
     *
     * @param integer $customclientId
     * @return UserCustomclient
     */
    public function setCustomclientId($customclientId)
    {
        $this->customclientId = $customclientId;

        return $this;
    }

    /**
     * Get customclientId
     *
     * @return integer 
     */
    public function getCustomclientId()
    {
        return $this->customclientId;
    }
}
