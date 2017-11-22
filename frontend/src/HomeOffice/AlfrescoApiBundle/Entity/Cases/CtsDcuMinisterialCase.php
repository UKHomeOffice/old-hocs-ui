<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\Cases;

use DateTime;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\ReplyToMember;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\ReplyToMemberDetails;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\StandardDetails;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\HomeSecretaryReply;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\CorrespondentDetails;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\ReplyToNumberTenCopy;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CtsDcuMinisterialCase
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity\Cases
 *
 * @Assert\Callback(groups={"Case_Draft"}, methods={"validate"})
 */
class CtsDcuMinisterialCase extends CtsCase
{
    use ReplyToMember,
        ReplyToMemberDetails,
        StandardDetails,
        HomeSecretaryReply,
        CorrespondentDetails,
        ReplyToNumberTenCopy;
 
    /**
     * @var DateTime
     */
    protected $poTarget;
 
    /**
     * @var DateTime
     */
    protected $draftResponseTarget;
 
    /**
     * @var DateTime
     */
    protected $allocateTarget;
 
    /**
     * @var DateTime
     */
    protected $dispatchTarget;
 
    /**
     *
     * @var string
     *
     * @Assert\NotBlank(groups={"Case_Draft"}, message="Select a response channel")
     */
    protected $hmpoResponse;

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
     * @return DateTime
     */
    public function getPoTarget()
    {
        return $this->poTarget;
    }

    /**
     * @param DateTime $poTarget
     */
    public function setPoTarget($poTarget)
    {
        $this->poTarget = DateHelper::forceDateTimeOrBlank($poTarget);
    }
 
    /**
     * @return DateTime
     */
    public function getDraftResponseTarget()
    {
        return $this->draftResponseTarget;
    }

    /**
     * @param DateTime $draftResponseTarget
     */
    public function setDraftResponseTarget($draftResponseTarget)
    {
        $this->draftResponseTarget = DateHelper::forceDateTimeOrBlank($draftResponseTarget);
    }
 
    /**
     * @return DateTime
     */
    public function getAllocateTarget()
    {
        return $this->allocateTarget;
    }

    /**
     * @param DateTime $allocateTarget
     */
    public function setAllocateTarget($allocateTarget)
    {
        $this->allocateTarget = DateHelper::forceDateTimeOrBlank($allocateTarget);
    }
 
    /**
     * @return DateTime
     */
    public function getDispatchTarget()
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
     * @return string
     */
    public function getHmpoResponse()
    {
        return $this->hmpoResponse;
    }

    /**
     * @param string $hmpoResonse
     */
    public function setHmpoResponse($hmpoResonse)
    {
        $this->hmpoResponse = $hmpoResonse;
    }

 
    /**
     * @return string
     */
    public function getCanonicalCorrespondent()
    {
        return $this->replyToName;
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
     * Get ThirdPartyCorrespondentTitle
     *
     * @return string
     */
    public function getThirdPartyCorrespondentTitle()
    {
        return $this->thirdPartyCorrespondentTitle;
    }

    /**
     * Set ThirdPartyCorrespondentTitle
     *
     * @param string $thirdPartyCorrespondentTitle
     *
     * @return $this
     */
    public function setThirdPartyCorrespondentTitle($thirdPartyCorrespondentTitle)
    {
        $this->thirdPartyCorrespondentTitle = $thirdPartyCorrespondentTitle;
        return $this;
    }

    /**
     * Get ThirdPartyCorrespondentForename
     *
     * @return string
     */
    public function getThirdPartyCorrespondentForename()
    {
        return $this->thirdPartyCorrespondentForename;
    }

    /**
     * Set ThirdPartyCorrespondentForename
     *
     * @param string $thirdPartyCorrespondentForename
     *
     * @return $this
     */
    public function setThirdPartyCorrespondentForename($thirdPartyCorrespondentForename)
    {
        $this->thirdPartyCorrespondentForename = $thirdPartyCorrespondentForename;
        return $this;
    }

    /**
     * Get ThirdPartyCorrespondentSurname
     *
     * @return string
     */
    public function getThirdPartyCorrespondentSurname()
    {
        return $this->thirdPartyCorrespondentSurname;
    }

    /**
     * Set ThirdPartyCorrespondentSurname
     *
     * @param string $thirdPartyCorrespondentSurname
     *
     * @return $this
     */
    public function setThirdPartyCorrespondentSurname($thirdPartyCorrespondentSurname)
    {
        $this->thirdPartyCorrespondentSurname = $thirdPartyCorrespondentSurname;
        return $this;
    }

    /**
     * Get ThirdPartyCorrespondentOrganisation
     *
     * @return string
     */
    public function getThirdPartyCorrespondentOrganisation()
    {
        return $this->thirdPartyCorrespondentOrganisation;
    }

    /**
     * Set ThirdPartyCorrespondentOrganisation
     *
     * @param string $thirdPartyCorrespondentOrganisation
     *
     * @return $this
     */
    public function setThirdPartyCorrespondentOrganisation($thirdPartyCorrespondentOrganisation)
    {
        $this->thirdPartyCorrespondentOrganisation = $thirdPartyCorrespondentOrganisation;
        return $this;
    }

    /**
     * Get ThirdPartyCorrespondentTelephone
     *
     * @return string
     */
    public function getThirdPartyCorrespondentTelephone()
    {
        return $this->thirdPartyCorrespondentTelephone;
    }

    /**
     * Set ThirdPartyCorrespondentTelephone
     *
     * @param string $thirdPartyCorrespondentTelephone
     *
     * @return $this
     */
    public function setThirdPartyCorrespondentTelephone($thirdPartyCorrespondentTelephone)
    {
        $this->thirdPartyCorrespondentTelephone = $thirdPartyCorrespondentTelephone;
        return $this;
    }

    /**
     * Get ThirdPartyCorrespondentEmail
     *
     * @return string
     */
    public function getThirdPartyCorrespondentEmail()
    {
        return $this->thirdPartyCorrespondentEmail;
    }

    /**
     * Set ThirdPartyCorrespondentEmail
     *
     * @param string $thirdPartyCorrespondentEmail
     *
     * @return $this
     */
    public function setThirdPartyCorrespondentEmail($thirdPartyCorrespondentEmail)
    {
        $this->thirdPartyCorrespondentEmail = $thirdPartyCorrespondentEmail;
        return $this;
    }

    /**
     * Get ThirdPartyCorrespondentPostcode
     *
     * @return string
     */
    public function getThirdPartyCorrespondentPostcode()
    {
        return $this->thirdPartyCorrespondentPostcode;
    }

    /**
     * Set ThirdPartyCorrespondentPostcode
     *
     * @param string $thirdPartyCorrespondentPostcode
     *
     * @return $this
     */
    public function setThirdPartyCorrespondentPostcode($thirdPartyCorrespondentPostcode)
    {
        $this->thirdPartyCorrespondentPostcode = $thirdPartyCorrespondentPostcode;
        return $this;
    }

    /**
     * Get ThirdPartyCorrespondentAddressLine1
     *
     * @return string
     */
    public function getThirdPartyCorrespondentAddressLine1()
    {
        return $this->thirdPartyCorrespondentAddressLine1;
    }

    /**
     * Set ThirdPartyCorrespondentAddressLine1
     *
     * @param string $thirdPartyCorrespondentAddressLine1
     *
     * @return $this
     */
    public function setThirdPartyCorrespondentAddressLine1($thirdPartyCorrespondentAddressLine1)
    {
        $this->thirdPartyCorrespondentAddressLine1 = $thirdPartyCorrespondentAddressLine1;
        return $this;
    }

    /**
     * Get ThirdPartyCorrespondentAddressLine2
     *
     * @return string
     */
    public function getThirdPartyCorrespondentAddressLine2()
    {
        return $this->thirdPartyCorrespondentAddressLine2;
    }

    /**
     * Set ThirdPartyCorrespondentAddressLine2
     *
     * @param string $thirdPartyCorrespondentAddressLine2
     *
     * @return $this
     */
    public function setThirdPartyCorrespondentAddressLine2($thirdPartyCorrespondentAddressLine2)
    {
        $this->thirdPartyCorrespondentAddressLine2 = $thirdPartyCorrespondentAddressLine2;
        return $this;
    }

    /**
     * Get ThirdPartyCorrespondentAddressLine3
     *
     * @return string
     */
    public function getThirdPartyCorrespondentAddressLine3()
    {
        return $this->thirdPartyCorrespondentAddressLine3;
    }

    /**
     * Set ThirdPartyCorrespondentAddressLine3
     *
     * @param string $thirdPartyCorrespondentAddressLine3
     *
     * @return $this
     */
    public function setThirdPartyCorrespondentAddressLine3($thirdPartyCorrespondentAddressLine3)
    {
        $this->thirdPartyCorrespondentAddressLine3 = $thirdPartyCorrespondentAddressLine3;
        return $this;
    }

    /**
     * Get Correspondent Address
     *
     * @param string $delimiter
     *
     * @return string
     */
    public function getThirdPartyCorrespondentAddress($delimiter = ', ')
    {
        return
            ($this->getThirdPartyCorrespondentAddressLine1() ? $this->getThirdPartyCorrespondentAddressLine1() . $delimiter : null).
            ($this->getThirdPartyCorrespondentAddressLine2() ? $this->getThirdPartyCorrespondentAddressLine2() . $delimiter : null).
            ($this->getThirdPartyCorrespondentAddressLine3() ? $this->getThirdPartyCorrespondentAddressLine3() . $delimiter : null).
            ($this->getThirdPartyCorrespondentPostcode() ?: null);
    }

    /**
     * Get ThirdPartyCorrespondentCountry
     *
     * @return string
     */
    public function getThirdPartyCorrespondentCountry()
    {
        return $this->thirdPartyCorrespondentCountry;
    }

    /**
     * Set ThirdPartyCorrespondentCountry
     *
     * @param string $thirdPartyCorrespondentCountry
     *
     * @return $this
     */
    public function setThirdPartyCorrespondentCountry($thirdPartyCorrespondentCountry)
    {
        $this->thirdPartyCorrespondentCountry = $thirdPartyCorrespondentCountry;
        return $this;
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
        if ($this->mpRef != null) {
            $moreDetailsString .= '<div><label>MP ref: </label><span>'.$this->mpRef.'</span></div>';
        }
        $homeSecReply = $this->getHomeSecretaryReply() ? 'Yes' : 'No';
        $moreDetailsString .= '<div><label>HS reply: </label><span>'.$homeSecReply.'</span></div>';
        return $moreDetailsString;
    }

    /**
     *
     * Method for validating - email address is required if response is email
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->hmpoResponse == 'Email' && is_null($this->replyToEmail)) {
            $context->buildViolation('You must enter a valid email address.')
                ->atPath('replyToEmail')
                ->addViolation();
        }
    }
}
