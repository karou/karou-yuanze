<?php

namespace Albatross\AceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomwaveQuestionnaire
 *
 * @ORM\Table(name="customwave_questionnaire")
 * @ORM\Entity
 */
class CustomwaveQuestionnaire
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Albatross\AceBundle\Entity\Questionnaire
     *
     * @ORM\ManyToOne(targetEntity="Albatross\AceBundle\Entity\Questionnaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="questionnaire_id", referencedColumnName="id")
     * })
     */
    private $questionnaire;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set questionnaire
     *
     * @param \Albatross\AceBundle\Entity\Questionnaire $questionnaire
     * @return CustomwaveQuestionnaire
     */
    public function setQuestionnaire(\Albatross\AceBundle\Entity\Questionnaire $questionnaire = null)
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }

    /**
     * Get questionnaire
     *
     * @return \Albatross\AceBundle\Entity\Questionnaire 
     */
    public function getQuestionnaire()
    {
        return $this->questionnaire;
    }

    /**
     * Set customwave
     *
     * @param \Albatross\AceBundle\Entity\Customwave $customwave
     * @return CustomwaveQuestionnaire
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
