<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MeetingRecap
 *
 * @ORM\Table(name="meeting_recap")
 * @ORM\Entity
 */
class MeetingRecap
{
    /**
     * @var string
     *
     * @ORM\Column(name="pm_attendee", type="string", length=255, nullable=true)
     */
    private $pmAttendee;

    /**
     * @var string
     *
     * @ORM\Column(name="op_attendee", type="string", length=255, nullable=true)
     */
    private $opAttendee;

    /**
     * @var string
     *
     * @ORM\Column(name="va_attendee", type="string", length=255, nullable=true)
     */
    private $vaAttendee;

    /**
     * @var string
     *
     * @ORM\Column(name="qc_attendee", type="string", length=255, nullable=true)
     */
    private $qcAttendee;

    /**
     * @var string
     *
     * @ORM\Column(name="report_attendee", type="string", length=255, nullable=true)
     */
    private $reportAttendee;

    /**
     * @var string
     *
     * @ORM\Column(name="text_1", type="text", nullable=true)
     */
    private $text1;

    /**
     * @var string
     *
     * @ORM\Column(name="text_2", type="text", nullable=true)
     */
    private $text2;

    /**
     * @var string
     *
     * @ORM\Column(name="text_3", type="text", nullable=true)
     */
    private $text3;

    /**
     * @var string
     *
     * @ORM\Column(name="text_4", type="text", nullable=true)
     */
    private $text4;

    /**
     * @var string
     *
     * @ORM\Column(name="text_5", type="text", nullable=true)
     */
    private $text5;

    /**
     * @var string
     *
     * @ORM\Column(name="text_6", type="text", nullable=true)
     */
    private $text6;

    /**
     * @var string
     *
     * @ORM\Column(name="text_7", type="text", nullable=true)
     */
    private $text7;

    /**
     * @var string
     *
     * @ORM\Column(name="text_8", type="text", nullable=true)
     */
    private $text8;

    /**
     * @var string
     *
     * @ORM\Column(name="text_9", type="text", nullable=true)
     */
    private $text9;

    /**
     * @var string
     *
     * @ORM\Column(name="text_10", type="text", nullable=true)
     */
    private $text10;

    /**
     * @var string
     *
     * @ORM\Column(name="text_11", type="text", nullable=true)
     */
    private $text11;

    /**
     * @var string
     *
     * @ORM\Column(name="text_12", type="text", nullable=true)
     */
    private $text12;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\Customwave
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Customwave")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customwave_id", referencedColumnName="id")
     * })
     */
    private $customwave;



    /**
     * Set pmAttendee
     *
     * @param string $pmAttendee
     * @return MeetingRecap
     */
    public function setPmAttendee($pmAttendee)
    {
        $this->pmAttendee = $pmAttendee;

        return $this;
    }

    /**
     * Get pmAttendee
     *
     * @return string 
     */
    public function getPmAttendee()
    {
        return $this->pmAttendee;
    }

    /**
     * Set opAttendee
     *
     * @param string $opAttendee
     * @return MeetingRecap
     */
    public function setOpAttendee($opAttendee)
    {
        $this->opAttendee = $opAttendee;

        return $this;
    }

    /**
     * Get opAttendee
     *
     * @return string 
     */
    public function getOpAttendee()
    {
        return $this->opAttendee;
    }

    /**
     * Set vaAttendee
     *
     * @param string $vaAttendee
     * @return MeetingRecap
     */
    public function setVaAttendee($vaAttendee)
    {
        $this->vaAttendee = $vaAttendee;

        return $this;
    }

    /**
     * Get vaAttendee
     *
     * @return string 
     */
    public function getVaAttendee()
    {
        return $this->vaAttendee;
    }

    /**
     * Set qcAttendee
     *
     * @param string $qcAttendee
     * @return MeetingRecap
     */
    public function setQcAttendee($qcAttendee)
    {
        $this->qcAttendee = $qcAttendee;

        return $this;
    }

    /**
     * Get qcAttendee
     *
     * @return string 
     */
    public function getQcAttendee()
    {
        return $this->qcAttendee;
    }

    /**
     * Set reportAttendee
     *
     * @param string $reportAttendee
     * @return MeetingRecap
     */
    public function setReportAttendee($reportAttendee)
    {
        $this->reportAttendee = $reportAttendee;

        return $this;
    }

    /**
     * Get reportAttendee
     *
     * @return string 
     */
    public function getReportAttendee()
    {
        return $this->reportAttendee;
    }

    /**
     * Set text1
     *
     * @param string $text1
     * @return MeetingRecap
     */
    public function setText1($text1)
    {
        $this->text1 = $text1;

        return $this;
    }

    /**
     * Get text1
     *
     * @return string 
     */
    public function getText1()
    {
        return $this->text1;
    }

    /**
     * Set text2
     *
     * @param string $text2
     * @return MeetingRecap
     */
    public function setText2($text2)
    {
        $this->text2 = $text2;

        return $this;
    }

    /**
     * Get text2
     *
     * @return string 
     */
    public function getText2()
    {
        return $this->text2;
    }

    /**
     * Set text3
     *
     * @param string $text3
     * @return MeetingRecap
     */
    public function setText3($text3)
    {
        $this->text3 = $text3;

        return $this;
    }

    /**
     * Get text3
     *
     * @return string 
     */
    public function getText3()
    {
        return $this->text3;
    }

    /**
     * Set text4
     *
     * @param string $text4
     * @return MeetingRecap
     */
    public function setText4($text4)
    {
        $this->text4 = $text4;

        return $this;
    }

    /**
     * Get text4
     *
     * @return string 
     */
    public function getText4()
    {
        return $this->text4;
    }

    /**
     * Set text5
     *
     * @param string $text5
     * @return MeetingRecap
     */
    public function setText5($text5)
    {
        $this->text5 = $text5;

        return $this;
    }

    /**
     * Get text5
     *
     * @return string 
     */
    public function getText5()
    {
        return $this->text5;
    }

    /**
     * Set text6
     *
     * @param string $text6
     * @return MeetingRecap
     */
    public function setText6($text6)
    {
        $this->text6 = $text6;

        return $this;
    }

    /**
     * Get text6
     *
     * @return string 
     */
    public function getText6()
    {
        return $this->text6;
    }

    /**
     * Set text7
     *
     * @param string $text7
     * @return MeetingRecap
     */
    public function setText7($text7)
    {
        $this->text7 = $text7;

        return $this;
    }

    /**
     * Get text7
     *
     * @return string 
     */
    public function getText7()
    {
        return $this->text7;
    }

    /**
     * Set text8
     *
     * @param string $text8
     * @return MeetingRecap
     */
    public function setText8($text8)
    {
        $this->text8 = $text8;

        return $this;
    }

    /**
     * Get text8
     *
     * @return string 
     */
    public function getText8()
    {
        return $this->text8;
    }

    /**
     * Set text9
     *
     * @param string $text9
     * @return MeetingRecap
     */
    public function setText9($text9)
    {
        $this->text9 = $text9;

        return $this;
    }

    /**
     * Get text9
     *
     * @return string 
     */
    public function getText9()
    {
        return $this->text9;
    }

    /**
     * Set text10
     *
     * @param string $text10
     * @return MeetingRecap
     */
    public function setText10($text10)
    {
        $this->text10 = $text10;

        return $this;
    }

    /**
     * Get text10
     *
     * @return string 
     */
    public function getText10()
    {
        return $this->text10;
    }

    /**
     * Set text11
     *
     * @param string $text11
     * @return MeetingRecap
     */
    public function setText11($text11)
    {
        $this->text11 = $text11;

        return $this;
    }

    /**
     * Get text11
     *
     * @return string 
     */
    public function getText11()
    {
        return $this->text11;
    }

    /**
     * Set text12
     *
     * @param string $text12
     * @return MeetingRecap
     */
    public function setText12($text12)
    {
        $this->text12 = $text12;

        return $this;
    }

    /**
     * Get text12
     *
     * @return string 
     */
    public function getText12()
    {
        return $this->text12;
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
     * Set customwave
     *
     * @param \Albatross\AceBundle\Entity\Customwave $customwave
     * @return MeetingRecap
     */
    public function setCustomwave(\Albatross\AceBundle\Entity\Customwave $customwave = null)
    {
        $this->customwave = $customwave;

        return $this;
    }

    /**
     * Get customwave
     *
     * @return \Albatross\AceBundle\Entity\Customwave 
     */
    public function getCustomwave()
    {
        return $this->customwave;
    }
}
