<?php

namespace Albatross\CustomBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * KickOffMeetingRecap
 */
class KickOffMeetingRecap
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $pm_attendee;

    /**
     * @var string
     */
    private $op_attendee;

    /**
     * @var string
     */
    private $va_attendee;

    /**
     * @var string
     */
    private $qc_attendee;

    /**
     * @var string
     */
    private $report_attendee;

    /**
     * @var string
     */
    private $text_1;

    /**
     * @var string
     */
    private $text_2;

    /**
     * @var string
     */
    private $text_3;

    /**
     * @var string
     */
    private $text_4;

    /**
     * @var string
     */
    private $text_5;

    /**
     * @var string
     */
    private $text_6;

    /**
     * @var string
     */
    private $text_7;

    /**
     * @var string
     */
    private $text_8;

    /**
     * @var string
     */
    private $text_9;

    /**
     * @var string
     */
    private $text_10;

    /**
     * @var string
     */
    private $text_11;

    /**
     * @var \Albatross\CustomBundle\Entity\Customwave
     */
    private $customwave;


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
     * Set pm_attendee
     *
     * @param string $pmAttendee
     * @return KickOffMeetingRecap
     */
    public function setPmAttendee($pmAttendee)
    {
        $this->pm_attendee = $pmAttendee;

        return $this;
    }

    /**
     * Get pm_attendee
     *
     * @return string 
     */
    public function getPmAttendee()
    {
        return $this->pm_attendee;
    }

    /**
     * Set op_attendee
     *
     * @param string $opAttendee
     * @return KickOffMeetingRecap
     */
    public function setOpAttendee($opAttendee)
    {
        $this->op_attendee = $opAttendee;

        return $this;
    }

    /**
     * Get op_attendee
     *
     * @return string 
     */
    public function getOpAttendee()
    {
        return $this->op_attendee;
    }

    /**
     * Set va_attendee
     *
     * @param string $vaAttendee
     * @return KickOffMeetingRecap
     */
    public function setVaAttendee($vaAttendee)
    {
        $this->va_attendee = $vaAttendee;

        return $this;
    }

    /**
     * Get va_attendee
     *
     * @return string 
     */
    public function getVaAttendee()
    {
        return $this->va_attendee;
    }

    /**
     * Set qc_attendee
     *
     * @param string $qcAttendee
     * @return KickOffMeetingRecap
     */
    public function setQcAttendee($qcAttendee)
    {
        $this->qc_attendee = $qcAttendee;

        return $this;
    }

    /**
     * Get qc_attendee
     *
     * @return string 
     */
    public function getQcAttendee()
    {
        return $this->qc_attendee;
    }

    /**
     * Set report_attendee
     *
     * @param string $reportAttendee
     * @return KickOffMeetingRecap
     */
    public function setReportAttendee($reportAttendee)
    {
        $this->report_attendee = $reportAttendee;

        return $this;
    }

    /**
     * Get report_attendee
     *
     * @return string 
     */
    public function getReportAttendee()
    {
        return $this->report_attendee;
    }

    /**
     * Set text_1
     *
     * @param string $text1
     * @return KickOffMeetingRecap
     */
    public function setText1($text1)
    {
        $this->text_1 = $text1;

        return $this;
    }

    /**
     * Get text_1
     *
     * @return string 
     */
    public function getText1()
    {
        return $this->text_1;
    }

    /**
     * Set text_2
     *
     * @param string $text2
     * @return KickOffMeetingRecap
     */
    public function setText2($text2)
    {
        $this->text_2 = $text2;

        return $this;
    }

    /**
     * Get text_2
     *
     * @return string 
     */
    public function getText2()
    {
        return $this->text_2;
    }

    /**
     * Set text_3
     *
     * @param string $text3
     * @return KickOffMeetingRecap
     */
    public function setText3($text3)
    {
        $this->text_3 = $text3;

        return $this;
    }

    /**
     * Get text_3
     *
     * @return string 
     */
    public function getText3()
    {
        return $this->text_3;
    }

    /**
     * Set text_4
     *
     * @param string $text4
     * @return KickOffMeetingRecap
     */
    public function setText4($text4)
    {
        $this->text_4 = $text4;

        return $this;
    }

    /**
     * Get text_4
     *
     * @return string 
     */
    public function getText4()
    {
        return $this->text_4;
    }

    /**
     * Set text_5
     *
     * @param string $text5
     * @return KickOffMeetingRecap
     */
    public function setText5($text5)
    {
        $this->text_5 = $text5;

        return $this;
    }

    /**
     * Get text_5
     *
     * @return string 
     */
    public function getText5()
    {
        return $this->text_5;
    }

    /**
     * Set text_6
     *
     * @param string $text6
     * @return KickOffMeetingRecap
     */
    public function setText6($text6)
    {
        $this->text_6 = $text6;

        return $this;
    }

    /**
     * Get text_6
     *
     * @return string 
     */
    public function getText6()
    {
        return $this->text_6;
    }

    /**
     * Set text_7
     *
     * @param string $text7
     * @return KickOffMeetingRecap
     */
    public function setText7($text7)
    {
        $this->text_7 = $text7;

        return $this;
    }

    /**
     * Get text_7
     *
     * @return string 
     */
    public function getText7()
    {
        return $this->text_7;
    }

    /**
     * Set text_8
     *
     * @param string $text8
     * @return KickOffMeetingRecap
     */
    public function setText8($text8)
    {
        $this->text_8 = $text8;

        return $this;
    }

    /**
     * Get text_8
     *
     * @return string 
     */
    public function getText8()
    {
        return $this->text_8;
    }

    /**
     * Set text_9
     *
     * @param string $text9
     * @return KickOffMeetingRecap
     */
    public function setText9($text9)
    {
        $this->text_9 = $text9;

        return $this;
    }

    /**
     * Get text_9
     *
     * @return string 
     */
    public function getText9()
    {
        return $this->text_9;
    }

    /**
     * Set text_10
     *
     * @param string $text10
     * @return KickOffMeetingRecap
     */
    public function setText10($text10)
    {
        $this->text_10 = $text10;

        return $this;
    }

    /**
     * Get text_10
     *
     * @return string 
     */
    public function getText10()
    {
        return $this->text_10;
    }

    /**
     * Set text_11
     *
     * @param string $text11
     * @return KickOffMeetingRecap
     */
    public function setText11($text11)
    {
        $this->text_11 = $text11;

        return $this;
    }

    /**
     * Get text_11
     *
     * @return string 
     */
    public function getText11()
    {
        return $this->text_11;
    }

    /**
     * Set customwave
     *
     * @param \Albatross\CustomBundle\Entity\Customwave $customwave
     * @return KickOffMeetingRecap
     */
    public function setCustomwave(\Albatross\CustomBundle\Entity\Customwave $customwave = null)
    {
        $this->customwave = $customwave;

        return $this;
    }

    /**
     * Get customwave
     *
     * @return \Albatross\CustomBundle\Entity\Customwave 
     */
    public function getCustomwave()
    {
        return $this->customwave;
    }
    /**
     * @var string
     */
    private $text_12;


    /**
     * Set text_12
     *
     * @param string $text12
     * @return KickOffMeetingRecap
     */
    public function setText12($text12)
    {
        $this->text_12 = $text12;

        return $this;
    }

    /**
     * Get text_12
     *
     * @return string 
     */
    public function getText12()
    {
        return $this->text_12;
    }
}
