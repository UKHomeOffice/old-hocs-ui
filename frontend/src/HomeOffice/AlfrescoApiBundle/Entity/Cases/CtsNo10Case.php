<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\CorrespondentDetails;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\ReplyToMember;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\ReplyToMemberDetails;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\StandardDetails;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\HomeSecretaryReply;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use Symfony\Component\Validator\Constraints as Assert;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\ReplyToNumberTenCopy;

class CtsNo10Case extends CtsCase
{
    use
        ReplyToMemberDetails,
        StandardDetails,
        HomeSecretaryReply,
        ReplyToNumberTenCopy,
        CorrespondentDetails;

    /**
     * @var string
     */
    public $allocateTo;

    /**
     * @var string
     *
     */
    protected $member;

    /**
     * @var \DateTime $draftDate
     *
     * @Assert\NotBlank(groups={"Case_Create_DTEN"}, message="Select a draft date")
     */
    protected $draftDate;

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
     * @return string
     */
    public function getDraftDate()
    {
        return $this->draftDate;
    }

    /**
     * @param $draftDate
     */
    public function setDraftDate($draftDate)
    {
        $this->draftDate = DateHelper::forceDateTimeOrBlank($draftDate);
    }

    /**
     * @return string
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * @param string $member
     */
    public function setMember($member)
    {
        $this->member = $member;
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
     * @return string
     */
    public function getCanonicalCorrespondent()
    {
        return $this->getMember();
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
        if ($this->mpRef != null) {
            $moreDetailsString .= '<div><label>MP ref: </label><span>'.$this->mpRef.'</span></div>';
        }
        return $moreDetailsString;
    }
}
