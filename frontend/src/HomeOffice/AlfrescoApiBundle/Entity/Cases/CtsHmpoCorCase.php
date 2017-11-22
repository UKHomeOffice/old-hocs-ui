<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents as Components;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;

/**
 * Class CtsHmpoCorCase
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity\Cases
 */
class CtsHmpoCorCase extends CtsCase
{
    use Components\PrimaryCorrespondent;
    use Components\SecondaryCorrespondent;
    use Components\TertiaryCorrespondent;

    /**
     * @var \DateTime $dateReceived
     */
    protected $dateReceived;

    /**
     * @var string
     */
    protected $channel;

    /**
     *
     * @var string
     */
    protected $hmpoResponse;

    /**
     * @var string
     */
    protected $passportNumber;

    /**
     * @var string
     */
    protected $applicationNumber;

    /**
     * @var bool
     */
    protected $priority;

    /**
     * @var string
     */
    protected $officeOfOrigin;

    /**
     * @var string
     */
    protected $hmpoRefundDecision;

    /**
     * @var string
     */
    protected $hmpoRefundAmount;

    /**
     * @var string
     */
    protected $hmpoComplaintOutcome;

    /**
     * @var \DateTime
     */
    protected $bringUpDate;

    /**
     * @var string
     */
    protected $deferDueTo;

    /**
     * Get DateReceived
     *
     * @return \DateTime
     */
    public function getDateReceived()
    {
        return $this->dateReceived;
    }

    /**
     * Set DateReceived
     *
     * @param \DateTime $dateReceived
     *
     * @return $this
     */
    public function setDateReceived($dateReceived)
    {
        $this->dateReceived = DateHelper::forceDateTimeOrBlank($dateReceived);

        return $this;
    }

    /**
     * Get Channel
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Set Channel
     *
     * @param string $channel
     *
     * @return $this
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get HmpoResponse
     *
     * @return string
     */
    public function getHmpoResponse()
    {
        return $this->hmpoResponse;
    }

    /**
     * Set HmpoResponse
     *
     * @param string $hmpoResponse
     *
     * @return $this
     */
    public function setHmpoResponse($hmpoResponse)
    {
        $this->hmpoResponse = $hmpoResponse;

        return $this;
    }

    /**
     * Get PassportNumber
     *
     * @return string
     */
    public function getPassportNumber()
    {
        return $this->passportNumber;
    }

    /**
     * Set PassportNumber
     *
     * @param string $passportNumber
     *
     * @return $this
     */
    public function setPassportNumber($passportNumber)
    {
        $this->passportNumber = $passportNumber;

        return $this;
    }

    /**
     * Get ApplicationNumber
     *
     * @return string
     */
    public function getApplicationNumber()
    {
        return $this->applicationNumber;
    }

    /**
     * Set ApplicationNumber
     *
     * @param string $applicationNumber
     *
     * @return $this
     */
    public function setApplicationNumber($applicationNumber)
    {
        $this->applicationNumber = $applicationNumber;

        return $this;
    }

    /**
     * Get Priority
     *
     * @return bool
     */
    public function isPriority()
    {
        return $this->priority;
    }

    /**
     * Set Priority
     *
     * @param bool $priority
     *
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get OfficeOfOrigin
     *
     * @return string
     */
    public function getOfficeOfOrigin()
    {
        return $this->officeOfOrigin;
    }

    /**
     * Set OfficeOfOrigin
     *
     * @param string $officeOfOrigin
     *
     * @return $this
     */
    public function setOfficeOfOrigin($officeOfOrigin)
    {
        $this->officeOfOrigin = $officeOfOrigin;

        return $this;
    }

    /**
     * Get HmpoRefundDecision
     *
     * @return string
     */
    public function getHmpoRefundDecision()
    {
        return $this->hmpoRefundDecision;
    }

    /**
     * Set HmpoRefundDecision
     *
     * @param string $hmpoRefundDecision
     *
     * @return $this
     */
    public function setHmpoRefundDecision($hmpoRefundDecision)
    {
        $this->hmpoRefundDecision = $hmpoRefundDecision;

        return $this;
    }

    /**
     * Get HmpoRefundAmount
     *
     * @return string
     */
    public function getHmpoRefundAmount()
    {
        return $this->hmpoRefundAmount;
    }

    /**
     * Set HmpoRefundAmount
     *
     * @param string $hmpoRefundAmount
     *
     * @return $this
     */
    public function setHmpoRefundAmount($hmpoRefundAmount)
    {
        $this->hmpoRefundAmount = $hmpoRefundAmount;

        return $this;
    }

    /**
     * Get HmpoComplaintOutcome
     *
     * @return string
     */
    public function getHmpoComplaintOutcome()
    {
        return $this->hmpoComplaintOutcome;
    }

    /**
     * Set HmpoComplaintOutcome
     *
     * @param string $hmpoComplaintOutcome
     *
     * @return $this
     */
    public function setHmpoComplaintOutcome($hmpoComplaintOutcome)
    {
        $this->hmpoComplaintOutcome = $hmpoComplaintOutcome;

        return $this;
    }

    /**
     * Get DeferDueTo
     *
     * @return string
     */
    public function getDeferDueTo()
    {
        return $this->deferDueTo;
    }

    /**
     * Set DeferDueTo
     *
     * @param string $deferDueTo
     *
     * @return $this
     */
    public function setDeferDueTo($deferDueTo)
    {
        $this->deferDueTo = $deferDueTo;

        return $this;
    }

    /**
     * Get BringUpDate
     *
     * @return \DateTime
     */
    public function getBringUpDate()
    {
        return $this->bringUpDate;
    }

    /**
     * @param \DateTime|string $bringUpDate
     *
     * @return $this
     */
    public function setBringUpDate($bringUpDate)
    {
        $this->bringUpDate = DateHelper::forceDateTimeOrBlank($bringUpDate);

        return $this;
    }
}
