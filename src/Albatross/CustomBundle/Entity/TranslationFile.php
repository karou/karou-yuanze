<?php

namespace Albatross\CustomBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TranslationFile
 */
class TranslationFile
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $path;

    /**
     * @var integer
     */
    private $language_index;

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
     * Set path
     *
     * @param string $path
     * @return TranslationFile
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set language_index
     *
     * @param integer $languageIndex
     * @return TranslationFile
     */
    public function setLanguageIndex($languageIndex)
    {
        $this->language_index = $languageIndex;

        return $this;
    }

    /**
     * Get language_index
     *
     * @return integer 
     */
    public function getLanguageIndex()
    {
        return $this->language_index;
    }

    private $file_index;


    /**
     * Set file_index
     *
     * @param integer $fileIndex
     * @return TranslationFile
     */
    public function setFileIndex($fileIndex)
    {
        $this->file_index = $fileIndex;

        return $this;
    }

    /**
     * Get file_index
     *
     * @return integer 
     */
    public function getFileIndex()
    {
        return $this->file_index;
    }


    /**
     * Set customfield
     *
     * @param \Albatross\CustomBundle\Entity\Customfield $customfield
     * @return TranslationFile
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
}
