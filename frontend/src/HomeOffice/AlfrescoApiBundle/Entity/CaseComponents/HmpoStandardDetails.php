<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\CaseComponents;

use DateTime;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use Symfony\Component\Validator\Constraints as Assert;

trait HmpoStandardDetails
{
    /**
     * @var DateTime $dateReceived
     *
     * @Assert\NotBlank()
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
     *
     * @var boolean
     */
    protected $replyToCorrespondent;

    /**
     *
     * @var string
     */
    protected $typeOfCorrespondent;

    /**
     *
     * @var boolean
     */
    protected $replyToApplicant;

    /**
     *
     * @var string
     */
    protected $applicantTitle;

    /**
     *
     * @var string
     */
    protected $applicantForename;

    /**
     *
     * @var string
     */
    protected $applicantSurname;

    /**
     *
     * @var string
     */
    protected $applicantAddressLine1;

    /**
     *
     * @var string
     */
    protected $applicantAddressLine2;

    /**
     *
     * @var string
     */
    protected $applicantAddressLine3;

    /**
     *
     * @var string
     */
    protected $applicantPostcode;

    /**
     *
     * @var string
     */
    protected $applicantCountry;

    /**
     *
     * @var string
     */
    protected $applicantEmail;

    /**
     *
     * @var string
     */
    protected $applicantTelephone;

    /**
     * @var string
     */
    protected $passportNumber;

    /**
     * @var string
     */
    protected $applicationNumber;

    /**
     * @return string
     */
    public function getApplicationNumber()
    {
        return $this->applicationNumber;
    }

    /**
     * @param string $applicationNumber
     */
    public function setApplicationNumber($applicationNumber)
    {
        $this->applicationNumber = $applicationNumber;
    }

    /**
     * @return string
     */
    public function getPassportNumber()
    {
        return $this->passportNumber;
    }

    /**
     * @param string $passportNumber
     */
    public function setPassportNumber($passportNumber)
    {
        $this->passportNumber = $passportNumber;
    }

    /**
     * @return DateTime
     */
    public function getDateReceived()
    {
        return $this->dateReceived;
    }

    /**
     * @param DateTime $dateReceived
     */
    public function setDateReceived($dateReceived)
    {
        $this->dateReceived = DateHelper::forceDateTimeOrBlank($dateReceived);
    }

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
     * @return string
     */
    public function getHmpoResponse()
    {
        return $this->hmpoResponse;
    }

    /**
     * @param string $hmpoResponse
     */
    public function setHmpoResponse($hmpoResponse)
    {
        $this->hmpoResponse = $hmpoResponse;
    }

    /**
     * @return string
     */
    public function getTypeOfCorrespondent()
    {
        return $this->typeOfCorrespondent;
    }

    /**
     * @param string $typeOfCorrespondent
     */
    public function setTypeOfCorrespondent($typeOfCorrespondent)
    {
        $this->typeOfCorrespondent = $typeOfCorrespondent;
    }

    /**
     * @return boolean
     */
    public function getReplyToCorrespondent()
    {
        return $this->replyToCorrespondent == "true" ? true : false;
    }

    /**
     *
     * @param boolean $replyToCorrespondent
     */
    public function setReplyToCorrespondent($replyToCorrespondent)
    {
        $this->replyToCorrespondent = $replyToCorrespondent;
    }

    /**
     * @return boolean
     */
    public function getReplyToApplicant()
    {
        return $this->replyToApplicant == "true" ? true : false;
    }

    /**
     * @return string
     */
    public function getApplicantTitle()
    {
        return $this->applicantTitle;
    }

    /**
     * @return string
     */
    public function getApplicantForename()
    {
        return $this->applicantForename;
    }

    /**
     * @return string
     */
    public function getApplicantSurname()
    {
        return $this->applicantSurname;
    }

    /**
     *
     * @return string
     */
    public function getApplicantFullName()
    {
        $fullName = null;
        if ($this->applicantForename != null) {
            $fullName .= $this->applicantForename . ' ';
        }
        if ($this->applicantSurname != null) {
            $fullName .= $this->applicantSurname;
        }
        return $fullName;
    }

    /**
     * @return string
     */
    public function getApplicantAddressLine1()
    {
        return $this->applicantAddressLine1;
    }

    /**
     * @return string
     */
    public function getApplicantAddressLine2()
    {
        return $this->applicantAddressLine2;
    }

    /**
     * @return string
     */
    public function getApplicantAddressLine3()
    {
        return $this->applicantAddressLine3;
    }

    /**
     * @return string
     */
    public function getApplicantPostcode()
    {
        return $this->applicantPostcode;
    }

    /**
     * @return string
     */
    public function getApplicantCountry()
    {
        return $this->applicantCountry;
    }

    /**
     * @return string
     */
    public function getApplicantEmail()
    {
        return $this->applicantEmail;
    }

    /**
     * @return string
     */
    public function getApplicantTelephone()
    {
        return $this->applicantTelephone;
    }

    /**
     * @param boolean $replyToApplicant
     */
    public function setReplyToApplicant($replyToApplicant)
    {
        $this->replyToApplicant = $replyToApplicant;
    }

    /**
     * @param string $applicantTitle
     */
    public function setApplicantTitle($applicantTitle)
    {
        $this->applicantTitle = $applicantTitle;
    }

    /**
     * @param string $applicantForename
     */
    public function setApplicantForename($applicantForename)
    {
        $this->applicantForename = $applicantForename;
    }

    /**
     * @param string $applicantSurname
     */
    public function setApplicantSurname($applicantSurname)
    {
        $this->applicantSurname = $applicantSurname;
    }

    /**
     * @param string $applicantAddressLine1
     */
    public function setApplicantAddressLine1($applicantAddressLine1)
    {
        $this->applicantAddressLine1 = $applicantAddressLine1;
    }

    /**
     * @param string $applicantAddressLine2
     */
    public function setApplicantAddressLine2($applicantAddressLine2)
    {
        $this->applicantAddressLine2 = $applicantAddressLine2;
    }

    /**
     * @param string $applicantAddressLine3
     */
    public function setApplicantAddressLine3($applicantAddressLine3)
    {
        $this->applicantAddressLine3 = $applicantAddressLine3;
    }

    /**
     * @param string $applicantPostcode
     */
    public function setApplicantPostcode($applicantPostcode)
    {
        $this->applicantPostcode = $applicantPostcode;
    }

    /**
     * @param string $applicantCountry
     */
    public function setApplicantCountry($applicantCountry)
    {
        $this->applicantCountry = $applicantCountry;
    }

    /**
     * @param string $applicantEmail
     */
    public function setApplicantEmail($applicantEmail)
    {
        $this->applicantEmail = $applicantEmail;
    }

    /**
     * @param string $applicantTelephone
     */
    public function setApplicantTelephone($applicantTelephone)
    {
        $this->applicantTelephone = $applicantTelephone;
    }
}
