<?php

namespace Albatross\CustomBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReportDeliverySchedule
 */
class ReportDeliverySchedule
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $updated_date;

    /**
     * @var integer
     */
    private $report_status;

    /**
     * @var \Albatross\UserBundle\Entity\User
     */
    private $project_manager;

    /**
     * @var \Albatross\CustomBundle\Entity\Customproject
     */
    private $customproject;


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
     * Set updated_date
     *
     * @param \DateTime $updatedDate
     * @return ReportDeliverySchedule
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updated_date = $updatedDate;

        return $this;
    }

    /**
     * Get updated_date
     *
     * @return \DateTime 
     */
    public function getUpdatedDate()
    {
        return $this->updated_date;
    }

    /**
     * Set report_status
     *
     * @param integer $reportStatus
     * @return ReportDeliverySchedule
     */
    public function setReportStatus($reportStatus)
    {
        $this->report_status = $reportStatus;

        return $this;
    }

    /**
     * Get report_status
     *
     * @return integer 
     */
    public function getReportStatus()
    {
        return $this->report_status;
    }

    /**
     * Set project_manager
     *
     * @param \Albatross\UserBundle\Entity\User $projectManager
     * @return ReportDeliverySchedule
     */
    public function setProjectManager(\Albatross\UserBundle\Entity\User $projectManager = null)
    {
        $this->project_manager = $projectManager;

        return $this;
    }

    /**
     * Get project_manager
     *
     * @return \Albatross\UserBundle\Entity\User 
     */
    public function getProjectManager()
    {
        return $this->project_manager;
    }

    /**
     * Set customproject
     *
     * @param \Albatross\CustomBundle\Entity\Customproject $customproject
     * @return ReportDeliverySchedule
     */
    public function setCustomproject(\Albatross\CustomBundle\Entity\Customproject $customproject = null)
    {
        $this->customproject = $customproject;

        return $this;
    }

    /**
     * Get customproject
     *
     * @return \Albatross\CustomBundle\Entity\Customproject 
     */
    public function getCustomproject()
    {
        return $this->customproject;
    }
}
