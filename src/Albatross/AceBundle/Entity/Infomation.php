<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Infomation
 *
 * @ORM\Table(name="infomation")
 * @ORM\Entity
 */
class Infomation
{
    /**
     * @var string
     *
     * @ORM\Column(name="new_pos_in_Wave", type="string", length=255, nullable=true)
     */
    private $newPosInWave;

    /**
     * @var string
     *
     * @ORM\Column(name="delete_pos_in_Wave", type="string", length=255, nullable=true)
     */
    private $deletePosInWave;

    /**
     * @var string
     *
     * @ORM\Column(name="invalids_to_be_invoiced", type="string", length=255, nullable=true)
     */
    private $invalidsToBeInvoiced;

    /**
     * @var string
     *
     * @ORM\Column(name="misfires_to_be_invoiced", type="string", length=255, nullable=true)
     */
    private $misfiresToBeInvoiced;

    /**
     * @var string
     *
     * @ORM\Column(name="purchases_made", type="string", length=255, nullable=true)
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
     * @var \Albatross\AceBundle\Entity\Recap
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Recap")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recap_id", referencedColumnName="id")
     * })
     */
    private $recap;



    /**
     * Set newPosInWave
     *
     * @param string $newPosInWave
     * @return Infomation
     */
    public function setNewPosInWave($newPosInWave)
    {
        $this->newPosInWave = $newPosInWave;

        return $this;
    }

    /**
     * Get newPosInWave
     *
     * @return string 
     */
    public function getNewPosInWave()
    {
        return $this->newPosInWave;
    }

    /**
     * Set deletePosInWave
     *
     * @param string $deletePosInWave
     * @return Infomation
     */
    public function setDeletePosInWave($deletePosInWave)
    {
        $this->deletePosInWave = $deletePosInWave;

        return $this;
    }

    /**
     * Get deletePosInWave
     *
     * @return string 
     */
    public function getDeletePosInWave()
    {
        return $this->deletePosInWave;
    }

    /**
     * Set invalidsToBeInvoiced
     *
     * @param string $invalidsToBeInvoiced
     * @return Infomation
     */
    public function setInvalidsToBeInvoiced($invalidsToBeInvoiced)
    {
        $this->invalidsToBeInvoiced = $invalidsToBeInvoiced;

        return $this;
    }

    /**
     * Get invalidsToBeInvoiced
     *
     * @return string 
     */
    public function getInvalidsToBeInvoiced()
    {
        return $this->invalidsToBeInvoiced;
    }

    /**
     * Set misfiresToBeInvoiced
     *
     * @param string $misfiresToBeInvoiced
     * @return Infomation
     */
    public function setMisfiresToBeInvoiced($misfiresToBeInvoiced)
    {
        $this->misfiresToBeInvoiced = $misfiresToBeInvoiced;

        return $this;
    }

    /**
     * Get misfiresToBeInvoiced
     *
     * @return string 
     */
    public function getMisfiresToBeInvoiced()
    {
        return $this->misfiresToBeInvoiced;
    }

    /**
     * Set purchasesMade
     *
     * @param string $purchasesMade
     * @return Infomation
     */
    public function setPurchasesMade($purchasesMade)
    {
        $this->purchasesMade = $purchasesMade;

        return $this;
    }

    /**
     * Get purchasesMade
     *
     * @return string 
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

    /**
     * Set recap
     *
     * @param \Albatross\AceBundle\Entity\Recap $recap
     * @return Infomation
     */
    public function setRecap(\Albatross\AceBundle\Entity\Recap $recap = null)
    {
        $this->recap = $recap;

        return $this;
    }

    /**
     * Get recap
     *
     * @return \Albatross\AceBundle\Entity\Recap 
     */
    public function getRecap()
    {
        return $this->recap;
    }
}
