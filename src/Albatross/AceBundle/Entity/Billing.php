<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Billing
 */
class Billing
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $BillingRate;

    /**
     * @var string
     */
    private $BillingCurr;

    /**
     * @var float
     */
    private $PayRate;

    /**
     * @var float
     */
    private $PrecalcBillingItemsSum;

    /**
     * @var integer
     */
    private $PrecalcBillingItemsCount;

    /**
     * @var float
     */
    private $PrecalcPayrollItemsSum;

    /**
     * @var integer
     */
    private $PrecalcPayrollItemsCount;

    /**
     * @var string
     */
    private $PayrollCurr;

    /**
     * @var \Albatross\AceBundle\Entity\Aolsurvey
     */
    private $aolsurvey;


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
     * Set BillingRate
     *
     * @param float $billingRate
     * @return Billing
     */
    public function setBillingRate($billingRate)
    {
        $this->BillingRate = $billingRate;

        return $this;
    }

    /**
     * Get BillingRate
     *
     * @return float 
     */
    public function getBillingRate()
    {
        return $this->BillingRate;
    }

    /**
     * Set BillingCurr
     *
     * @param string $billingCurr
     * @return Billing
     */
    public function setBillingCurr($billingCurr)
    {
        $this->BillingCurr = $billingCurr;

        return $this;
    }

    /**
     * Get BillingCurr
     *
     * @return string 
     */
    public function getBillingCurr()
    {
        return $this->BillingCurr;
    }

    /**
     * Set PayRate
     *
     * @param float $payRate
     * @return Billing
     */
    public function setPayRate($payRate)
    {
        $this->PayRate = $payRate;

        return $this;
    }

    /**
     * Get PayRate
     *
     * @return float 
     */
    public function getPayRate()
    {
        return $this->PayRate;
    }

    /**
     * Set PrecalcBillingItemsSum
     *
     * @param float $precalcBillingItemsSum
     * @return Billing
     */
    public function setPrecalcBillingItemsSum($precalcBillingItemsSum)
    {
        $this->PrecalcBillingItemsSum = $precalcBillingItemsSum;

        return $this;
    }

    /**
     * Get PrecalcBillingItemsSum
     *
     * @return float 
     */
    public function getPrecalcBillingItemsSum()
    {
        return $this->PrecalcBillingItemsSum;
    }

    /**
     * Set PrecalcBillingItemsCount
     *
     * @param integer $precalcBillingItemsCount
     * @return Billing
     */
    public function setPrecalcBillingItemsCount($precalcBillingItemsCount)
    {
        $this->PrecalcBillingItemsCount = $precalcBillingItemsCount;

        return $this;
    }

    /**
     * Get PrecalcBillingItemsCount
     *
     * @return integer 
     */
    public function getPrecalcBillingItemsCount()
    {
        return $this->PrecalcBillingItemsCount;
    }

    /**
     * Set PrecalcPayrollItemsSum
     *
     * @param float $precalcPayrollItemsSum
     * @return Billing
     */
    public function setPrecalcPayrollItemsSum($precalcPayrollItemsSum)
    {
        $this->PrecalcPayrollItemsSum = $precalcPayrollItemsSum;

        return $this;
    }

    /**
     * Get PrecalcPayrollItemsSum
     *
     * @return float 
     */
    public function getPrecalcPayrollItemsSum()
    {
        return $this->PrecalcPayrollItemsSum;
    }

    /**
     * Set PrecalcPayrollItemsCount
     *
     * @param integer $precalcPayrollItemsCount
     * @return Billing
     */
    public function setPrecalcPayrollItemsCount($precalcPayrollItemsCount)
    {
        $this->PrecalcPayrollItemsCount = $precalcPayrollItemsCount;

        return $this;
    }

    /**
     * Get PrecalcPayrollItemsCount
     *
     * @return integer 
     */
    public function getPrecalcPayrollItemsCount()
    {
        return $this->PrecalcPayrollItemsCount;
    }

    /**
     * Set PayrollCurr
     *
     * @param string $payrollCurr
     * @return Billing
     */
    public function setPayrollCurr($payrollCurr)
    {
        $this->PayrollCurr = $payrollCurr;

        return $this;
    }

    /**
     * Get PayrollCurr
     *
     * @return string 
     */
    public function getPayrollCurr()
    {
        return $this->PayrollCurr;
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
