<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Information
 *
 * @ORM\Table(name="information")
 * @ORM\Entity
 */
class Information
{
    /**
     * @var integer
     *
     * @ORM\Column(name="attachments_id", type="bigint", nullable=true)
     */
    private $attachmentsId;

    /**
     * @var integer
     *
     * @ORM\Column(name="new_pos_in_Wave", type="bigint", nullable=true)
     */
    private $newPosInWave;

    /**
     * @var integer
     *
     * @ORM\Column(name="delete_pos_in_Wave", type="bigint", nullable=true)
     */
    private $deletePosInWave;

    /**
     * @var integer
     *
     * @ORM\Column(name="invalids_to_be_invoiced", type="bigint", nullable=true)
     */
    private $invalidsToBeInvoiced;

    /**
     * @var integer
     *
     * @ORM\Column(name="misfires_to_be_invoiced", type="bigint", nullable=true)
     */
    private $misfiresToBeInvoiced;

    /**
     * @var integer
     *
     * @ORM\Column(name="purchases_made", type="bigint", nullable=true)
     */
    private $purchasesMade;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set attachmentsId
     *
     * @param integer $attachmentsId
     * @return Information
     */
    public function setAttachmentsId($attachmentsId)
    {
        $this->attachmentsId = $attachmentsId;

        return $this;
    }

    /**
     * Get attachmentsId
     *
     * @return integer 
     */
    public function getAttachmentsId()
    {
        return $this->attachmentsId;
    }

    /**
     * Set newPosInWave
     *
     * @param integer $newPosInWave
     * @return Information
     */
    public function setNewPosInWave($newPosInWave)
    {
        $this->newPosInWave = $newPosInWave;

        return $this;
    }

    /**
     * Get newPosInWave
     *
     * @return integer 
     */
    public function getNewPosInWave()
    {
        return $this->newPosInWave;
    }

    /**
     * Set deletePosInWave
     *
     * @param integer $deletePosInWave
     * @return Information
     */
    public function setDeletePosInWave($deletePosInWave)
    {
        $this->deletePosInWave = $deletePosInWave;

        return $this;
    }

    /**
     * Get deletePosInWave
     *
     * @return integer 
     */
    public function getDeletePosInWave()
    {
        return $this->deletePosInWave;
    }

    /**
     * Set invalidsToBeInvoiced
     *
     * @param integer $invalidsToBeInvoiced
     * @return Information
     */
    public function setInvalidsToBeInvoiced($invalidsToBeInvoiced)
    {
        $this->invalidsToBeInvoiced = $invalidsToBeInvoiced;

        return $this;
    }

    /**
     * Get invalidsToBeInvoiced
     *
     * @return integer 
     */
    public function getInvalidsToBeInvoiced()
    {
        return $this->invalidsToBeInvoiced;
    }

    /**
     * Set misfiresToBeInvoiced
     *
     * @param integer $misfiresToBeInvoiced
     * @return Information
     */
    public function setMisfiresToBeInvoiced($misfiresToBeInvoiced)
    {
        $this->misfiresToBeInvoiced = $misfiresToBeInvoiced;

        return $this;
    }

    /**
     * Get misfiresToBeInvoiced
     *
     * @return integer 
     */
    public function getMisfiresToBeInvoiced()
    {
        return $this->misfiresToBeInvoiced;
    }

    /**
     * Set purchasesMade
     *
     * @param integer $purchasesMade
     * @return Information
     */
    public function setPurchasesMade($purchasesMade)
    {
        $this->purchasesMade = $purchasesMade;

        return $this;
    }

    /**
     * Get purchasesMade
     *
     * @return integer 
     */
    public function getPurchasesMade()
    {
        return $this->purchasesMade;
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
}
