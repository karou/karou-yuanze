<?php

namespace Albatross\CustomBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Information
 */
class Infomation
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $new_pos_in_Wave;

    /**
     * @var integer
     */
    private $delete_pos_in_Wave;

    /**
     * @var integer
     */
    private $invalids_to_be_invoiced;

    /**
     * @var integer
     */
    private $misfires_to_be_invoiced;

    /**
     * @var integer
     */
    private $purchases_made;

    /**
     * @var \Albatross\CustomBundle\Entity\Recap
     */
    private $recap;


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
     * Set new_pos_in_Wave
     *
     * @param integer $newPosInWave
     * @return Information
     */
    public function setNewPosInWave($newPosInWave)
    {
        $this->new_pos_in_Wave = $newPosInWave;

        return $this;
    }

    /**
     * Get new_pos_in_Wave
     *
     * @return integer 
     */
    public function getNewPosInWave()
    {
        return $this->new_pos_in_Wave;
    }

    /**
     * Set delete_pos_in_Wave
     *
     * @param integer $deletePosInWave
     * @return Information
     */
    public function setDeletePosInWave($deletePosInWave)
    {
        $this->delete_pos_in_Wave = $deletePosInWave;

        return $this;
    }

    /**
     * Get delete_pos_in_Wave
     *
     * @return integer 
     */
    public function getDeletePosInWave()
    {
        return $this->delete_pos_in_Wave;
    }

    /**
     * Set invalids_to_be_invoiced
     *
     * @param integer $invalidsToBeInvoiced
     * @return Information
     */
    public function setInvalidsToBeInvoiced($invalidsToBeInvoiced)
    {
        $this->invalids_to_be_invoiced = $invalidsToBeInvoiced;

        return $this;
    }

    /**
     * Get invalids_to_be_invoiced
     *
     * @return integer 
     */
    public function getInvalidsToBeInvoiced()
    {
        return $this->invalids_to_be_invoiced;
    }

    /**
     * Set misfires_to_be_invoiced
     *
     * @param integer $misfiresToBeInvoiced
     * @return Information
     */
    public function setMisfiresToBeInvoiced($misfiresToBeInvoiced)
    {
        $this->misfires_to_be_invoiced = $misfiresToBeInvoiced;

        return $this;
    }

    /**
     * Get misfires_to_be_invoiced
     *
     * @return integer 
     */
    public function getMisfiresToBeInvoiced()
    {
        return $this->misfires_to_be_invoiced;
    }

    /**
     * Set purchases_made
     *
     * @param integer $purchasesMade
     * @return Information
     */
    public function setPurchasesMade($purchasesMade)
    {
        $this->purchases_made = $purchasesMade;

        return $this;
    }

    /**
     * Get purchases_made
     *
     * @return integer 
     */
    public function getPurchasesMade()
    {
        return $this->purchases_made;
    }

    /**
     * Set recap
     *
     * @param \Albatross\CustomBundle\Entity\Recap $recap
     * @return Information
     */
    public function setRecap(\Albatross\CustomBundle\Entity\Recap $recap = null)
    {
        $this->recap = $recap;

        return $this;
    }

    /**
     * Get recap
     *
     * @return \Albatross\CustomBundle\Entity\Recap 
     */
    public function getRecap()
    {
        return $this->recap;
    }
}
