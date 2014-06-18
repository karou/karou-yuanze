<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Billing
 *
 * @ORM\Table(name="billing")
 * @ORM\Entity
 */
class Billing
{
    /**
     * @var float
     *
     * @ORM\Column(name="billing_rate", type="float", nullable=false)
     */
    private $billingRate;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_curr", type="string", length=255, nullable=false)
     */
    private $billingCurr;

    /**
     * @var float
     *
     * @ORM\Column(name="pay_rate", type="float", nullable=true)
     */
    private $payRate;

    /**
     * @var float
     *
     * @ORM\Column(name="precalc_billing_items_sum", type="float", nullable=false)
     */
    private $precalcBillingItemsSum;

    /**
     * @var integer
     *
     * @ORM\Column(name="precalc_billing_items_count", type="smallint", nullable=false)
     */
    private $precalcBillingItemsCount;

    /**
     * @var float
     *
     * @ORM\Column(name="precalc_payroll_items_sum", type="float", nullable=false)
     */
    private $precalcPayrollItemsSum;

    /**
     * @var integer
     *
     * @ORM\Column(name="precalc_payroll_items_count", type="smallint", nullable=false)
     */
    private $precalcPayrollItemsCount;

    /**
     * @var string
     *
     * @ORM\Column(name="payroll_curr", type="string", length=255, nullable=false)
     */
    private $payrollCurr;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\Aolsurvey
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Aolsurvey")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="aolsurvey_id", referencedColumnName="id")
     * })
     */
    private $aolsurvey;



    /**
     * Set billingRate
     *
     * @param float $billingRate
     * @return Billing
     */
    public function setBillingRate($billingRate)
    {
        $this->billingRate = $billingRate;

        return $this;
    }

    /**
     * Get billingRate
     *
     * @return float 
     */
    public function getBillingRate()
    {
        return $this->billingRate;
    }

    /**
     * Set billingCurr
     *
     * @param string $billingCurr
     * @return Billing
     */
    public function setBillingCurr($billingCurr)
    {
        $this->billingCurr = $billingCurr;

        return $this;
    }

    /**
     * Get billingCurr
     *
     * @return string 
     */
    public function getBillingCurr()
    {
        return $this->billingCurr;
    }

    /**
     * Set payRate
     *
     * @param float $payRate
     * @return Billing
     */
    public function setPayRate($payRate)
    {
        $this->payRate = $payRate;

        return $this;
    }

    /**
     * Get payRate
     *
     * @return float 
     */
    public function getPayRate()
    {
        return $this->payRate;
    }

    /**
     * Set precalcBillingItemsSum
     *
     * @param float $precalcBillingItemsSum
     * @return Billing
     */
    public function setPrecalcBillingItemsSum($precalcBillingItemsSum)
    {
        $this->precalcBillingItemsSum = $precalcBillingItemsSum;

        return $this;
    }

    /**
     * Get precalcBillingItemsSum
     *
     * @return float 
     */
    public function getPrecalcBillingItemsSum()
    {
        return $this->precalcBillingItemsSum;
    }

    /**
     * Set precalcBillingItemsCount
     *
     * @param integer $precalcBillingItemsCount
     * @return Billing
     */
    public function setPrecalcBillingItemsCount($precalcBillingItemsCount)
    {
        $this->precalcBillingItemsCount = $precalcBillingItemsCount;

        return $this;
    }

    /**
     * Get precalcBillingItemsCount
     *
     * @return integer 
     */
    public function getPrecalcBillingItemsCount()
    {
        return $this->precalcBillingItemsCount;
    }

    /**
     * Set precalcPayrollItemsSum
     *
     * @param float $precalcPayrollItemsSum
     * @return Billing
     */
    public function setPrecalcPayrollItemsSum($precalcPayrollItemsSum)
    {
        $this->precalcPayrollItemsSum = $precalcPayrollItemsSum;

        return $this;
    }

    /**
     * Get precalcPayrollItemsSum
     *
     * @return float 
     */
    public function getPrecalcPayrollItemsSum()
    {
        return $this->precalcPayrollItemsSum;
    }

    /**
     * Set precalcPayrollItemsCount
     *
     * @param integer $precalcPayrollItemsCount
     * @return Billing
     */
    public function setPrecalcPayrollItemsCount($precalcPayrollItemsCount)
    {
        $this->precalcPayrollItemsCount = $precalcPayrollItemsCount;

        return $this;
    }

    /**
     * Get precalcPayrollItemsCount
     *
     * @return integer 
     */
    public function getPrecalcPayrollItemsCount()
    {
        return $this->precalcPayrollItemsCount;
    }

    /**
     * Set payrollCurr
     *
     * @param string $payrollCurr
     * @return Billing
     */
    public function setPayrollCurr($payrollCurr)
    {
        $this->payrollCurr = $payrollCurr;

        return $this;
    }

    /**
     * Get payrollCurr
     *
     * @return string 
     */
    public function getPayrollCurr()
    {
        return $this->payrollCurr;
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
     * Set aolsurvey
     *
     * @param \Albatross\AceBundle\Entity\Aolsurvey $aolsurvey
     * @return Billing
     */
    public function setAolsurvey(\Albatross\AceBundle\Entity\Aolsurvey $aolsurvey = null)
    {
        $this->aolsurvey = $aolsurvey;

        return $this;
    }

    /**
     * Get aolsurvey
     *
     * @return \Albatross\AceBundle\Entity\Aolsurvey 
     */
    public function getAolsurvey()
    {
        return $this->aolsurvey;
    }
}
