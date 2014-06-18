<?php

namespace Albatross\UserBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * User
 */
class User implements AdvancedUserInterface {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $create_at;

    /**
     * @var \DateTime
     */
    private $update_at;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password, $encode = true) {
        if (!$password)
            $this->password = null;
        else {
            if ($encode)
                $this->password = sha1($password);
            else
                $this->password = $password;
        }
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set create_at
     *
     * @param \DateTime $createAt
     * @return User
     */
    public function setCreateAt($createAt) {
        $this->create_at = $createAt;

        return $this;
    }

    /**
     * Get create_at
     *
     * @return \DateTime 
     */
    public function getCreateAt() {
        return $this->create_at;
    }

    /**
     * Set update_at
     *
     * @param \DateTime $updateAt
     * @return User
     */
    public function setUpdateAt($updateAt) {
        $this->update_at = $updateAt;

        return $this;
    }

    /**
     * Get update_at
     *
     * @return \DateTime 
     */
    public function getUpdateAt() {
        return $this->update_at;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue() {
        // Add your code here
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue() {
        // Add your code here
    }

    public function __toString() {
        return $this->username;
    }

    public function getRoles() {
        
        if ($this->type == 0){
            $roles = array('ROLE_USER', 'ROLE_TYPE_USER');
        }else {
            $roles = array('ROLE_TYPE_CLIENT');
        }
        if(empty($this->identity)){
            $roles = $roles;
        }else{
            $roles[] = 'ROLE_' . strtoupper($this->identity->getParameters());
        }
        
        return $roles;
    }

    public function eraseCredentials() {
        return;
    }

    public function getSalt() {
        return;
    }

    /**
     * @var string
     */
    private $pic;

    /**
     * Set pic
     *
     * @param string $pic
     * @return User
     */
    public function setPic($pic) {
        $this->pic = $pic;

        return $this;
    }

    /**
     * Get pic
     *
     * @return string 
     */
    public function getPic() {
        return $this->pic;
    }

    /**
     * @var string
     */
    private $aol_username;

    /**
     * @var string
     */
    private $aol_password;

    /**
     * @var string
     */
    private $ace_username;

    /**
     * @var string
     */
    private $ace_password;

    /**
     * Set aol_username
     *
     * @param string $aolUsername
     * @return User
     */
    public function setAolUsername($aolUsername) {
        $this->aol_username = $aolUsername;

        return $this;
    }

    /**
     * Get aol_username
     *
     * @return string 
     */
    public function getAolUsername() {
        return $this->aol_username;
    }

    /**
     * Set aol_password
     *
     * @param string $aolPassword
     * @return User
     */
    public function setAolPassword($aolPassword) {
        $this->aol_password = base64_encode($aolPassword);

        return $this;
    }

    /**
     * Get aol_password
     *
     * @return string 
     */
    public function getAolPassword($decode = false) {
        if ($decode)
            return base64_decode($this->aol_password);
        return $this->aol_password;
    }

    /**
     * Set ace_username
     *
     * @param string $aceUsername
     * @return User
     */
    public function setAceUsername($aceUsername) {
        $this->ace_username = $aceUsername;

        return $this;
    }

    /**
     * Get ace_username
     *
     * @return string 
     */
    public function getAceUsername() {
        return $this->ace_username;
    }

    /**
     * Set ace_password
     *
     * @param string $acePassword
     * @return User
     */
    public function setAcePassword($acePassword) {
        $this->ace_password = base64_encode($acePassword);

        return $this;
    }

    /**
     * Get ace_password
     *
     * @return string 
     */
    public function getAcePassword($decode = false) {
        if ($decode)
            return base64_decode($this->ace_password);
        return $this->ace_password;
    }

    private $file;
    

    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
    }

    public function getFile() {
        return $this->file;
    }

    public function upload() {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the
        // target filename to move to
        // do whatever you want to generate a unique name
        $filename = sha1(uniqid(mt_rand(), true));
        $filename = $filename.'.'.$this->getFile()->guessExtension();
        $this->getFile()->move(
                $this->getUploadRootDir(), $filename
                );

        // set the path property to the filename where you've saved the file
        $this->pic = $filename;

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    protected function getUploadDir() {
        return 'uploads/user_picture';
    }

    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    public function getWebPath() {
        return null === $this->pic ? null : $this->getUploadDir() . '/' . $this->pic;
    }

    public function getAbsolutePath() {
        return null === $this->pic ? null : $this->getUploadRootDir() . '/' . $this->pic;
    }
    
    /**
     * @var string
     */
    private $fullname;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $skype;

    /**
     * @var string
     */
    private $mobile;

    /**
     * @var string
     */
    private $office_phone;

    /**
     * @var string
     */
    private $office_address;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $crm_username;

    /**
     * @var string
     */
    private $crm_password;


    /**
     * Set fullname
     *
     * @param string $fullname
     * @return User
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string 
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return User
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set skype
     *
     * @param string $skype
     * @return User
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;

        return $this;
    }

    /**
     * Get skype
     *
     * @return string 
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return User
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set office_phone
     *
     * @param string $officePhone
     * @return User
     */
    public function setOfficePhone($officePhone)
    {
        $this->office_phone = $officePhone;

        return $this;
    }

    /**
     * Get office_phone
     *
     * @return string 
     */
    public function getOfficePhone()
    {
        return $this->office_phone;
    }

    /**
     * Set office_address
     *
     * @param string $officeAddress
     * @return User
     */
    public function setOfficeAddress($officeAddress)
    {
        $this->office_address = $officeAddress;

        return $this;
    }

    /**
     * Get office_address
     *
     * @return string 
     */
    public function getOfficeAddress()
    {
        return $this->office_address;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set crm_username
     *
     * @param string $crmUsername
     * @return User
     */
    public function setCrmUsername($crmUsername)
    {
        $this->crm_username = $crmUsername;

        return $this;
    }

    /**
     * Get crm_username
     *
     * @return string 
     */
    public function getCrmUsername()
    {
        return $this->crm_username;
    }

    /**
     * Set crm_password
     *
     * @param string $crmPassword
     * @return User
     */
    public function setCrmPassword($crmPassword)
    {
        $this->crm_password = base64_encode($crmPassword);

        return $this;
    }

    /**
     * Get crm_password
     *
     * @return string 
     */
    public function getCrmPassword($decode = false)
    {
        if ($decode)
            return base64_decode($this->ace_password);
        return $this->crm_password;
    }
    /**
     * @var integer
     */
    private $type;


    /**
     * Set type
     *
     * @param integer $type
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $recap;


    /**
     * Add recap
     *
     * @param \Albatross\CustomBundle\Entity\Recap $recap
     * @return User
     */
    public function addRecap(\Albatross\CustomBundle\Entity\Recap $recap)
    {
        $this->recap[] = $recap;

        return $this;
    }

    /**
     * Remove recap
     *
     * @param \Albatross\CustomBundle\Entity\Recap $recap
     */
    public function removeRecap(\Albatross\CustomBundle\Entity\Recap $recap)
    {
        $this->recap->removeElement($recap);
    }

    /**
     * Get recap
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecap()
    {
        return $this->recap;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $customproject;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $customclient;


    /**
     * Add customproject
     *
     * @param \Albatross\CustomBundle\Entity\Customproject $customproject
     * @return User
     */
    public function addCustomproject(\Albatross\CustomBundle\Entity\Customproject $customproject)
    {
        $this->customproject[] = $customproject;

        return $this;
    }

    /**
     * Remove customproject
     *
     * @param \Albatross\CustomBundle\Entity\Customproject $customproject
     */
    public function removeCustomproject(\Albatross\CustomBundle\Entity\Customproject $customproject)
    {
        $this->customproject->removeElement($customproject);
    }

    /**
     * Get customproject
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomproject()
    {
        return $this->customproject;
    }

    /**
     * Add customclient
     *
     * @param \Albatross\CustomBundle\Entity\Customclient $customclient
     * @return User
     */
    public function addCustomclient(\Albatross\CustomBundle\Entity\Customclient $customclient)
    {
        $this->customclient[] = $customclient;

        return $this;
    }

    /**
     * Remove customclient
     *
     * @param \Albatross\CustomBundle\Entity\Customclient $customclient
     */
    public function removeCustomclient(\Albatross\CustomBundle\Entity\Customclient $customclient)
    {
        $this->customclient->removeElement($customclient);
    }

    /**
     * Get customclient
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomclient()
    {
        return $this->customclient;
    }
    /**
     * @var \Albatross\UserBundle\Entity\Identity
     */
    private $identity;

    /**
     * @var \Albatross\UserBundle\Entity\Position
     */
    private $position;


    /**
     * Set identity
     *
     * @param \Albatross\UserBundle\Entity\Identity $identity
     * @return User
     */
    public function setIdentity(\Albatross\UserBundle\Entity\Identity $identity = null)
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * Get identity
     *
     * @return \Albatross\UserBundle\Entity\Identity 
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * Set position
     *
     * @param \Albatross\UserBundle\Entity\Position $position
     * @return User
     */
    public function setPosition(\Albatross\UserBundle\Entity\Position $position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return \Albatross\UserBundle\Entity\Position 
     */
    public function getPosition()
    {
        return $this->position;
    }
    
    public function __sleep()
    {
        return array('id');
    }
    /**
     * @var string
     */
    private $status;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recap = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customproject = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customclient = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set status
     *
     * @param string $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
    public function isAccountNonExpired()
    {
        return true;
    }
    public function isAccountNonLocked()
    {
        return true;
    }
    public function isCredentialsNonExpired()
    {
        return true;
    }
    public function isEnabled() {
        $status = $this->status;
        if($status == 'active')
            return true;
        else if($status == 'deleted')
            return false;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $log;


    /**
     * Add log
     *
     * @param \Albatross\UserBundle\Entity\Log $log
     * @return User
     */
    public function addLog(\Albatross\UserBundle\Entity\Log $log)
    {
        $this->log[] = $log;

        return $this;
    }

    /**
     * Remove log
     *
     * @param \Albatross\UserBundle\Entity\Log $log
     */
    public function removeLog(\Albatross\UserBundle\Entity\Log $log)
    {
        $this->log->removeElement($log);
    }

    /**
     * Get log
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLog()
    {
        return $this->log;
    }
}
