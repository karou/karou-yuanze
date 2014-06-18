<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserCustomproject
 *
 * @ORM\Table(name="user_customproject")
 * @ORM\Entity
 */
class UserCustomproject
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
     * @ORM\Column(name="customproject_id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $customprojectId;



    /**
     * Set userId
     *
     * @param integer $userId
     * @return UserCustomproject
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
     * Set customprojectId
     *
     * @param integer $customprojectId
     * @return UserCustomproject
     */
    public function setCustomprojectId($customprojectId)
    {
        $this->customprojectId = $customprojectId;

        return $this;
    }

    /**
     * Get customprojectId
     *
     * @return integer 
     */
    public function getCustomprojectId()
    {
        return $this->customprojectId;
    }
}
