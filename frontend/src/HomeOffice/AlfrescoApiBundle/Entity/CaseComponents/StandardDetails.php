<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\CaseComponents;

use \DateTime;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use Symfony\Component\Validator\Constraints as Assert;

trait StandardDetails
{

    /**
     * @var DateTime $dateReceived
     *
     * @Assert\NotBlank()
     */
    protected $dateReceived;

    /**
     * @var DateTime
     *
     * @Assert\NotBlank(groups={"Case_Create"}, message="Select the date of letter")
     */
    protected $dateOfLetter;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Case_Create_UKVI_B", "Case_DCU_Create"}, message="Select the original channel")
     */
    protected $channel;

    /**
     * @var boolean
     */
    protected $priority;

    /**
     * @var boolean
     */
    protected $advice;

    /**
     * @var string
     */
    protected $mpRef;
    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param string $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    /**
     * @return DateTime
     */
    public function getDateOfLetter()
    {
        return $this->dateOfLetter;
    }

    /**
     * @param string $dateOfLetter
     */
    public function setDateOfLetter($dateOfLetter)
    {
        $this->dateOfLetter = DateHelper::forceDateTimeOrBlank($dateOfLetter);
    }

    /**
     * @return DateTime
     */
    public function getDateReceived()
    {
        return $this->dateReceived;
    }

    /**
     * @param string $dateReceived
     */
    public function setDateReceived($dateReceived)
    {
        $this->dateReceived = DateHelper::forceDateTimeOrBlank($dateReceived);
    }

    /**
     * @return string
     */
    public function getMpRef()
    {
        return $this->mpRef;
    }

    /**
     * @param string $MPRef
     */
    public function setMPRef($MPRef)
    {
        $this->mpRef = $MPRef;
    }

    /**
     * @return boolean
     */
    public function getPriority()
    {
        return $this->priority == "true" ? true : false;
    }

    /**
     * @param string $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return boolean
     */
    public function getAdvice()
    {
        return $this->advice == "true" ? true : false;
    }

    /**
     * @param boolean $advice
     */
    public function setAdvice($advice)
    {
        $this->advice = $advice;
    }
}
