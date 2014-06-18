<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPermissiongroup
 *
 * @ORM\Table(name="user_permissiongroup")
 * @ORM\Entity
 */
class UserPermissiongroup
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
     * @ORM\Column(name="permissiongroup_id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $permissiongroupId;



    /**
     * Set userId
     *
     * @param integer $userId
     * @return UserPermissiongroup
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
     * Set permissiongroupId
     *
     * @param integer $permissiongroupId
     * @return UserPermissiongroup
     */
    public function setPermissiongroupId($permissiongroupId)
    {
        $this->permissiongroupId = $permissiongroupId;

        return $this;
    }

    /**
     * Get permissiongroupId
     *
     * @return integer 
     */
    public function getPermissiongroupId()
    {
        return $this->permissiongroupId;
    }
}
