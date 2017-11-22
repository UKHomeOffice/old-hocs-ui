<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\ReplyToMember;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\ReplyToMemberDetails;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\StandardDetails;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\CorrespondentDetails;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\ReplyToNumberTenCopy;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use Symfony\Component\Validator\Constraints as Assert;

class CtsUkviCase extends CtsCase
{
    use ReplyToMember,
        ReplyToMemberDetails,
        CorrespondentDetails,
        StandardDetails,
        ReplyToNumberTenCopy;

    /**
     * @var string
     */
    protected $caseRef;

    /**
     * @var string
     */
    protected $thirdPartyCorrespondentTitle;

    /**
     * @var string
     */
    protected $thirdPartyCorrespondentForename;

    /**
     * @var string
     */
    protected $thirdPartyCorrespondentSurname;

    /**
     * @var string
     */
    protected $thirdPartyCorrespondentOrganisation;

    /**
     * @var string
     */
    protected $thirdPartyCorrespondentTelephone;

    /**
     * @var string
     */
    protected $thirdPartyCorrespondentEmail;

    /**
     * @var string
     */
    protected $thirdPartyCorrespondentPostcode;

    /**
     * @var string
     */
    protected $thirdPartyCorrespondentAddressLine1;

    /**
     * @var string
     */
    protected $thirdPartyCorrespondentAddressLine2;

    /**
     * @var string
     */
    protected $thirdPartyCorrespondentAddressLine3;

    /**
     * @var string
     */
    protected $thirdPartyCorrespondentCountry;

    /**
     * @var \DateTime
     */
    protected $draftResponseTarget;

    /**
     * @var \DateTime
     */
    protected $allocateToResponderTarget;

    /**
     * @var \DateTime
     */
    protected $responderHubTarget;

    /**
     * @var  String
     */
    protected $hmpoResponse;

    /**
     * @var bool
     */
    protected $detainee;

    /**
     * @return string
     */
    public function getCaseRef()
    {
        return $this->caseRef;
    }

    /*
     * @param string
     */
    public function setCaseRef($caseRef)
    {
        $this->caseRef = $caseRef;
    }

    /**
     * @return string
     */
    public function getThirdPartyCorrespondentTitle()
    {
        return $this->thirdPartyCorrespondentTitle;
    }

    /*
     * @param string
     */
    public function setThirdPartyCorrespondentTitle($thirdPartyCorrespondentTitle)
    {
        $this->thirdPartyCorrespondentTitle = $thirdPartyCorrespondentTitle;
    }

    /**
     * @return string
     */
    public function getThirdPartyCorrespondentForename()
    {
        return $this->thirdPartyCorrespondentForename;
    }

    /*
     * @param string
     */
    public function setThirdPartyCorrespondentForename($thirdPartyCorrespondentForename)
    {
        $this->thirdPartyCorrespondentForename = $thirdPartyCorrespondentForename;
    }

    /**
     * @return string
     */
    public function getThirdPartyCorrespondentSurname()
    {
        return $this->thirdPartyCorrespondentSurname;
    }

    /*
     * @param string
     */
    public function setThirdPartyCorrespondentSurname($thirdPartyCorrespondentSurname)
    {
        $this->thirdPartyCorrespondentSurname = $thirdPartyCorrespondentSurname;
    }

    /**
     * @return string
     */
    public function getThirdPartyCorrespondentOrganisation()
    {
        return $this->thirdPartyCorrespondentOrganisation;
    }

    /*
     * @param string
     */
    public function setThirdPartyCorrespondentOrganisation($thirdPartyCorrespondentOrganisation)
    {
        $this->thirdPartyCorrespondentOrganisation = $thirdPartyCorrespondentOrganisation;
    }

    /**
     * @return string
     */
    public function getThirdPartyCorrespondentTelephone()
    {
        return $this->thirdPartyCorrespondentTelephone;
    }

    /*
     * @param string
     */
    public function setThirdPartyCorrespondentTelephone($thirdPartyCorrespondentTelephone)
    {
        $this->thirdPartyCorrespondentTelephone = $thirdPartyCorrespondentTelephone;
    }

    /**
     * @return string
     */
    public function getThirdPartyCorrespondentEmail()
    {
        return $this->thirdPartyCorrespondentEmail;
    }

    /*
     * @param string
     */
    public function setThirdPartyCorrespondentEmail($thirdPartyCorrespondentEmail)
    {
        $this->thirdPartyCorrespondentEmail = $thirdPartyCorrespondentEmail;
    }

    /**
     * @return string
     */
    public function getThirdPartyCorrespondentPostcode()
    {
        return $this->thirdPartyCorrespondentPostcode;
    }

    /*
     * @param string
     */
    public function setThirdPartyCorrespondentPostcode($thirdPartyCorrespondentPostcode)
    {
        $this->thirdPartyCorrespondentPostcode = $thirdPartyCorrespondentPostcode;
    }

    /**
     * @return string
     */
    public function getThirdPartyCorrespondentAddressLine1()
    {
        return $this->thirdPartyCorrespondentAddressLine1;
    }

    /*
     * @param string
     */
    public function setThirdPartyCorrespondentAddressLine1($thirdPartyCorrespondentAddressLine1)
    {
        $this->thirdPartyCorrespondentAddressLine1 = $thirdPartyCorrespondentAddressLine1;
    }

    /**
     * @return string
     */
    public function getThirdPartyCorrespondentAddressLine2()
    {
        return $this->thirdPartyCorrespondentAddressLine2;
    }

    /*
     * @param string
     */
    public function setThirdPartyCorrespondentAddressLine2($thirdPartyCorrespondentAddressLine2)
    {
        $this->thirdPartyCorrespondentAddressLine2 = $thirdPartyCorrespondentAddressLine2;
    }

    /**
     * @return string
     */
    public function getThirdPartyCorrespondentAddressLine3()
    {
        return $this->thirdPartyCorrespondentAddressLine3;
    }

    /*
     * @param string
     */
    public function setThirdPartyCorrespondentAddressLine3($thirdPartyCorrespondentAddressLine3)
    {
        $this->thirdPartyCorrespondentAddressLine3 = $thirdPartyCorrespondentAddressLine3;
    }

    /**
     * @return string
     */
    public function getThirdPartyCorrespondentCountry()
    {
        return $this->thirdPartyCorrespondentCountry;
    }

    /*
     * @param string
     */
    public function setThirdPartyCorrespondentCountry($thirdPartyCorrespondentCountry)
    {
        $this->thirdPartyCorrespondentCountry = $thirdPartyCorrespondentCountry;
    }

    /**
     * @return DateTime
     */
    public function getDraftResponseTarget()
    {
        return $this->draftResponseTarget;
    }

    /**
     * @param \DateTime $draftResponseTarget
     */
    public function setDraftResponseTarget($draftResponseTarget)
    {
        $this->draftResponseTarget = DateHelper::forceDateTimeOrBlank($draftResponseTarget);
    }

    /**
     * @return \DateTime
     */
    public function getAllocateToResponderTarget()
    {
        return $this->allocateToResponderTarget;
    }

    /**
     * @param \DateTime $allocateToResponderTarget
     */
    public function setAllocateToResponderTarget($allocateToResponderTarget)
    {
        $this->allocateToResponderTarget = DateHelper::forceDateTimeOrBlank($allocateToResponderTarget);
    }

    /**
     * @return \DateTime
     */
    public function getResponderHubTarget()
    {
        return $this->responderHubTarget;
    }

    /**
     * @param \DateTime $responderHubTarget
     */
    public function setResponderHubTarget($responderHubTarget)
    {
        $this->responderHubTarget = DateHelper::forceDateTimeOrBlank($responderHubTarget);
    }

    /**
     * @return string
     */
    public function getCanonicalCorrespondent()
    {
        return $this->getReplyToName();
    }

    /**
     * We actually deduce this value and therefore the setter is redundant.
     * We keep it here as we want the field mapped but we don't care to set.
     *
     * @return $this
     */
    public function setCorrespondentIsMemberOfParliament($value)
    {
        return $this;
    }

    /**
     * Returning true means that members details have been provided.
     * Returning false means that constituent details have been provided.
     * Returning null means we are unsure and therefore neither option is valid.
     *
     * @return bool|null
     */
    public function getCorrespondentIsMemberOfParliament()
    {
        return is_string($this->member) ? true : ($this->correspondentForename ? false : null);
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
        if ($this->caseRef != null) {
            $moreDetailsString .= '<div><label>Case ref: </label><span>'.$this->caseRef.'</span></div>';
        }
        return $moreDetailsString;
    }

    /**
     * @param $response
     * @return CtsUkviCase
     */
    public function setHmpoResponse($response)
    {
        $this->hmpoResponse = $response;

        return $this;
    }

    /**
     * @return String
     */
    public function getHmpoResponse()
    {
        return $this->hmpoResponse;
    }

    /**
     * Get Detainee
     *
     * @return boolean
     */
    public function isDetainee()
    {
        return $this->detainee ? true : false;
    }

    /**
     * Set Detainee
     *
     * @param boolean $detainee
     *
     * @return $this
     */
    public function setDetainee($detainee)
    {
        $this->detainee = $detainee ? true : false;

        return $this;
    }


}
