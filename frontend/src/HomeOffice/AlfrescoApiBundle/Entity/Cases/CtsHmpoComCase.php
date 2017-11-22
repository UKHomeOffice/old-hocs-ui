<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\CorrespondentDetails;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\HmpoStandardDetails;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;

/**
 * Class CtsHmpoComCase
 * 
 * @package HomeOffice\AlfrescoApiBundle\Entity\Cases
 */
class CtsHmpoComCase extends CtsCase
{
    use CorrespondentDetails, HmpoStandardDetails;
     
    /**
     *
     * @var string
     */
    private $typeOfComplainant;
 
    /**
     *
     * @var string
     */
    private $typeOfRepresentative;
 
    /**
     *
     * @var boolean
     */
    private $replyToComplainant;
 
    /**
     *
     * @var string
     */
    private $complainantTitle;
 
    /**
     *
     * @var string
     */
    private $complainantForename;
 
    /**
     *
     * @var string
     */
    private $complainantSurname;
 
    /**
     *
     * @var string
     */
    private $complainantAddressLine1;
 
    /**
     *
     * @var string
     */
    private $complainantAddressLine2;
 
    /**
     *
     * @var string
     */
    private $complainantAddressLine3;
 
    /**
     *
     * @var string
     */
    private $complainantPostcode;
 
    /**
     *
     * @var string
     */
    private $complainantCountry;
 
    /**
     *
     * @var string
     */
    private $complainantEmail;
 
    /**
     *
     * @var string
     */
    private $complainantTelephone;
 
    /**
     *
     * @var string
     */
    private $hmpoStage;
 
    /**
     *
     * @var string
     */
    private $hmpoRefundDecision;

    /**
     *
     * @var string
     */
    private $hmpoRefundAmount;

    /**
     *
     * @var string
     */
    private $hmpoComplaintOutcome;
 
    /**
     * @var \DateTime $draftResponseTarget
     */
    protected $draftResponseTarget;
 
    /**
     * @var \DateTime $dateReceived
     */
    protected $dispatchTarget;

    public function getTypeOfComplainant()
    {
        return $this->typeOfComplainant;
    }
 
    /**
     * @return string
     */
    public function getTypeOfRepresentative()
    {
        return $this->typeOfRepresentative;
    }
 
    /**
     * @return boolean
     */
    public function getReplyToComplainant()
    {
        return $this->replyToComplainant == "true" ? true : false;
    }
 
    /**
     * @return string
     */
    public function getComplainantTitle()
    {
        return $this->complainantTitle;
    }
 
    /**
     * @return string
     */
    public function getComplainantForename()
    {
        return $this->complainantForename;
    }
 
    /**
     * @return string
     */
    public function getComplainantSurname()
    {
        return $this->complainantSurname;
    }
 
    /**
     * @return string
     */
    public function getComplainantAddressLine1()
    {
        return $this->complainantAddressLine1;
    }
 
    /**
     * @return string
     */
    public function getComplainantAddressLine2()
    {
        return $this->complainantAddressLine2;
    }
 
    /**
     * @return string
     */
    public function getComplainantAddressLine3()
    {
        return $this->complainantAddressLine3;
    }
 
    /**
     * @return string
     */
    public function getComplainantPostcode()
    {
        return $this->complainantPostcode;
    }
 
    /**
     * @return string
     */
    public function getComplainantCountry()
    {
        return $this->complainantCountry;
    }
 
    /**
     * @return string
     */
    public function getComplainantEmail()
    {
        return $this->complainantEmail;
    }
 
    /**
     * @return string
     */
    public function getComplainantTelephone()
    {
        return $this->complainantTelephone;
    }

    /**
     * @param string $typeOfComplainant
     */
    public function setTypeOfComplainant($typeOfComplainant)
    {
        $this->typeOfComplainant = $typeOfComplainant;
    }

    /**
     * @param string $typeOfRepresentative
     */
    public function setTypeOfRepresentative($typeOfRepresentative)
    {
        $this->typeOfRepresentative = $typeOfRepresentative;
    }

    /**
     * @param boolean $replyToComplainant
     */
    public function setReplyToComplainant($replyToComplainant)
    {
        $this->replyToComplainant = $replyToComplainant;
    }

    /**
     * @param string $complainantTitle
     */
    public function setComplainantTitle($complainantTitle)
    {
        $this->complainantTitle = $complainantTitle;
    }

    /**
     * @param string $complainantForename
     */
    public function setComplainantForename($complainantForename)
    {
        $this->complainantForename = $complainantForename;
    }

    /**
     * @param string $complainantSurname
     */
    public function setComplainantSurname($complainantSurname)
    {
        $this->complainantSurname = $complainantSurname;
    }

    /**
     * @param string $complainantAddressLine1
     */
    public function setComplainantAddressLine1($complainantAddressLine1)
    {
        $this->complainantAddressLine1 = $complainantAddressLine1;
    }

    /**
     * @param string $complainantAddressLine2
     */
    public function setComplainantAddressLine2($complainantAddressLine2)
    {
        $this->complainantAddressLine2 = $complainantAddressLine2;
    }

    /**
     * @param string $complainantAddressLine3
     */
    public function setComplainantAddressLine3($complainantAddressLine3)
    {
        $this->complainantAddressLine3 = $complainantAddressLine3;
    }

    /**
     * @param string $complainantPostcode
     */
    public function setComplainantPostcode($complainantPostcode)
    {
        $this->complainantPostcode = $complainantPostcode;
    }

    /**
     * @param string $complainantCountry
     */
    public function setComplainantCountry($complainantCountry)
    {
        $this->complainantCountry = $complainantCountry;
    }

    /**
     * @param string $complainantEmail
     */
    public function setComplainantEmail($complainantEmail)
    {
        $this->complainantEmail = $complainantEmail;
    }

    /**
     * @param string $complainantTelephone
     */
    public function setComplainantTelephone($complainantTelephone)
    {
        $this->complainantTelephone = $complainantTelephone;
    }
 
    /**
     *
     * @return string
     */
    public function getHmpoStage()
    {
        return $this->hmpoStage;
    }

    /**
     *
     * @param string $hmpoStage
     */
    public function setHmpoStage($hmpoStage)
    {
        $this->hmpoStage = $hmpoStage;
    }
 
    /**
     *
     * @return boolean
     */
    public function getHmpoRefundDecision()
    {
        return $this->hmpoRefundDecision;
    }

    /**
     *
     * @return boolean
     */
    public function getHmpoRefundAmount()
    {
        return $this->hmpoRefundAmount;
    }

    /**
     *
     * @return boolean
     */
    public function getHmpoComplaintOutcome()
    {
        return $this->hmpoComplaintOutcome;
    }

    /**
     *
     * @param string $hmpoRefundDecision
     */
    public function setHmpoRefundDecision($hmpoRefundDecision)
    {
        $this->hmpoRefundDecision = $hmpoRefundDecision;
    }

    /**
     *
     * @param string $hmpoRefundAmount
     */
    public function setHmpoRefundAmount($hmpoRefundAmount)
    {
        $this->hmpoRefundAmount = $hmpoRefundAmount;
    }

    /**
     *
     * @param string $hmpoComplaintOutcome
     */
    public function setHmpoComplaintOutcome($hmpoComplaintOutcome)
    {
        $this->hmpoComplaintOutcome = $hmpoComplaintOutcome;
    }
 
    /**
     * @return DateTime
     */
    public function getDraftResponseTarget()
    {
        return $this->draftResponseTarget;
    }
    /**
     * @param DateTime $dateReceived
     */
    public function setDraftResponseTarget($draftResponseTarget)
    {
        $this->draftResponseTarget = DateHelper::forceDateTimeOrBlank($draftResponseTarget);
    }
 
    /**
     * @return DateTime
     */
    public function getdispatchTarget()
    {
        return $this->dispatchTarget;
    }
 
    /**
     * @param DateTime $dispatchTarget
     */
    public function setDispatchTarget($dispatchTarget)
    {
        $this->dispatchTarget = DateHelper::forceDateTimeOrBlank($dispatchTarget);
    }

    /**
     *
     * @return string
     */
    public function getCanonicalCorrespondent()
    {
        $nameColumnString = '';
        if ($this->getApplicantFullName() != null) {
            $nameColumnString = $this->getApplicantFullName();
        } elseif ($this->getCorrespondentFullName() != null) {
            $nameColumnString = $this->getCorrespondentFullName();
        }
        return $nameColumnString;
    }

    /**
     * Method for generating the text to go into the 'More details' column of search
     * @return string
     */
    public function generateMoreDetailsColumnForSearch()
    {
        $moreDetailsString = '';
        if ($this->correspondentPostcode != null) {
            $moreDetailsString .= '<div><label>Postcode: </label><span>'.$this->correspondentPostcode.'</span></div>';
        }
        if ($this->passportNumber != null) {
            $moreDetailsString .= '<div><label>Passport no: </label><span>'.$this->passportNumber.'</span></div>';
        }
        if ($this->applicationNumber != null) {
            $moreDetailsString .= '<div><label>Application no: </label><span>'.$this->applicationNumber.'</span></div>';
        }
        return $moreDetailsString;
    }
}
