<?php

namespace Albatross\CustomBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Customclient
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Customclient {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $customproject;

    /**
     * Constructor
     */
    public function __construct() {
        $this->customproject = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Customclient
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Add customproject
     *
     * @param \Albatross\CustomBundle\Entity\Customproject $customproject
     * @return Customclient
     */
    public function addCustomproject(\Albatross\CustomBundle\Entity\Customproject $customproject) {
        $this->customproject[] = $customproject;

        return $this;
    }

    /**
     * Remove customproject
     *
     * @param \Albatross\CustomBundle\Entity\Customproject $customproject
     */
    public function removeCustomproject(\Albatross\CustomBundle\Entity\Customproject $customproject) {
        $this->customproject->removeElement($customproject);
    }

    /**
     * Get customproject
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomproject() {
        return $this->customproject;
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * @var string
     */
    private $logo;

    public function getWebPath() {
        if ($this->logo)
            return $this->getUploadDir() . '/' . $this->id . '.' . $this->logo;
        else
            return "uploads/clients/nologo.png";
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/clients';
    }

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    private $temp;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
        // check if we have an old image path
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
        } else {
            $this->logo = 'initial';
        }
    }


    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->getFile()) {
            return;
        }
        $this->logo = $this->getFile()->guessExtension();
        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->temp);
            // clear the temp image path
            $this->temp = null;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->getFile()->move(
                $this->getUploadRootDir(), $this->id . '.' . $this->getFile()->guessExtension()
        );

        $this->setFile(null);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove() {
        $this->temp = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if (isset($this->temp)) {
            unlink($this->temp);
        }
    }

    public function getAbsolutePath() {
        return null === $this->logo ? null : $this->getUploadRootDir() . '/' . $this->id . '.' . $this->logo;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }


    /**
     * Set logo
     *
     * @param string $logo
     * @return Customclient
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $user;


    /**
     * Add user
     *
     * @param \Albatross\UserBundle\Entity\User $user
     * @return Customclient
     */
    public function addUser(\Albatross\UserBundle\Entity\User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Albatross\UserBundle\Entity\User $user
     */
    public function removeUser(\Albatross\UserBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @var \Albatross\CustomBundle\Entity\Clientgroup
     */
    private $clientgroup;


    /**
     * Set clientgroup
     *
     * @param \Albatross\CustomBundle\Entity\Clientgroup $clientgroup
     * @return Customclient
     */
    public function setClientgroup(\Albatross\CustomBundle\Entity\Clientgroup $clientgroup = null)
    {
        $this->clientgroup = $clientgroup;

        return $this;
    }

    /**
     * Get clientgroup
     *
     * @return \Albatross\CustomBundle\Entity\Clientgroup 
     */
    public function getClientgroup()
    {
        return $this->clientgroup;
    }
        /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $operationquestionnaire;

    /**
     * Add operationquestionnaire
     *
     * @param \Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire
     * @return Customclient
     */
    public function addOperationquestionnaire(\Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire)
    {
        $this->operationquestionnaire[] = $operationquestionnaire;

        return $this;
    }

    /**
     * Remove operationquestionnaire
     *
     * @param \Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire
     */
    public function removeOperationquestionnaire(\Albatross\OperationBundle\Entity\OperationQuestionnaire $operationquestionnaire)
    {
        $this->operationquestionnaire->removeElement($operationquestionnaire);
    }

    /**
     * Get operationquestionnaire
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperationquestionnaire()
    {
        return $this->operationquestionnaire;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $operationproject;


    /**
     * Add operationproject
     *
     * @param \Albatross\OperationBundle\Entity\OperationProject $operationproject
     * @return Customclient
     */
    public function addOperationproject(\Albatross\OperationBundle\Entity\OperationProject $operationproject)
    {
        $this->operationproject[] = $operationproject;

        return $this;
    }

    /**
     * Remove operationproject
     *
     * @param \Albatross\OperationBundle\Entity\OperationProject $operationproject
     */
    public function removeOperationproject(\Albatross\OperationBundle\Entity\OperationProject $operationproject)
    {
        $this->operationproject->removeElement($operationproject);
    }

    /**
     * Get operationproject
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperationproject()
    {
        return $this->operationproject;
    }
}
