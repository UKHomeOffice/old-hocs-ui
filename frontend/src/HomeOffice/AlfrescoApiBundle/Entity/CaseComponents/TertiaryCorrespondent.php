<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\CaseComponents;

/**
 * Class TertiaryCorrespondent
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity\CaseComponents
 */
trait TertiaryCorrespondent
{
    /**
     * @var string
     */
    private $thirdPartyTypeOfCorrespondent;

    /**
     * @var string
     */
    private $thirdPartyCorrespondentTitle;

    /**
     * @var string
     */
    private $thirdPartyCorrespondentForename;

    /**
     * @var string
     */
    private $thirdPartyCorrespondentSurname;

    /**
     * @var string
     */
    private $thirdPartyCorrespondentAddressLine1;

    /**
     * @var string
     */
    private $thirdPartyCorrespondentAddressLine2;

    /**
     * @var string
     */
    private $thirdPartyCorrespondentAddressLine3;

    /**
     * @var string
     */
    private $thirdPartyCorrespondentPostcode;

    /**
     * @var string
     */
    private $thirdPartyCorrespondentCountry;

    /**
     * @var string
     */
    private $thirdPartyCorrespondentTelephone;

    /**
     * @var string
     */
    private $thirdPartyCorrespondentEmail;

    /**
     * @var boolean
     */
    private $thirdPartyCorrespondentReplyTo;

    /**
     * @var string
     */
    private $thirdPartyCorrespondentTypeOfRepresentative;

    /**
     * @var boolean
     */
    private $thirdPartyCorrespondentConsentAttached;

    /**
     * Get ThirdPartyTypeOfCorrespondent
     *
     * @return string
     */
    public function getThirdPartyTypeOfCorrespondent()
    {
        return $this->thirdPartyTypeOfCorrespondent;
    }

    /**
     * Set ThirdPartyTypeOfCorrespondent
     *
     * @param string $thirdPartyTypeOfCorrespondent
     *
     * @return static
     */
    public function setThirdPartyTypeOfCorrespondent($thirdPartyTypeOfCorrespondent)
    {
        $this->thirdPartyTypeOfCorrespondent = $thirdPartyTypeOfCorrespondent;

        return $this;
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
     * @return static
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
     * @return static
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
     * @return static
     */
    public function setThirdPartyCorrespondentSurname($thirdPartyCorrespondentSurname)
    {
        $this->thirdPartyCorrespondentSurname = $thirdPartyCorrespondentSurname;

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
     * @return static
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
     * @return static
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
     * @return static
     */
    public function setThirdPartyCorrespondentAddressLine3($thirdPartyCorrespondentAddressLine3)
    {
        $this->thirdPartyCorrespondentAddressLine3 = $thirdPartyCorrespondentAddressLine3;

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
     * @return static
     */
    public function setThirdPartyCorrespondentPostcode($thirdPartyCorrespondentPostcode)
    {
        $this->thirdPartyCorrespondentPostcode = $thirdPartyCorrespondentPostcode;

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
     * @return static
     */
    public function setThirdPartyCorrespondentCountry($thirdPartyCorrespondentCountry)
    {
        $this->thirdPartyCorrespondentCountry = $thirdPartyCorrespondentCountry;

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
     * @return static
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
     * @return static
     */
    public function setThirdPartyCorrespondentEmail($thirdPartyCorrespondentEmail)
    {
        $this->thirdPartyCorrespondentEmail = $thirdPartyCorrespondentEmail;

        return $this;
    }

    /**
     * Get ThirdPartyCorrespondentReplyTo
     *
     * @return bool
     */
    public function isThirdPartyCorrespondentReplyTo()
    {
        return $this->thirdPartyCorrespondentReplyTo;
    }

    /**
     * Set ThirdPartyCorrespondentReplyTo
     *
     * @param bool $thirdPartyCorrespondentReplyTo
     *
     * @return static
     */
    public function setThirdPartyCorrespondentReplyTo($thirdPartyCorrespondentReplyTo)
    {
        $this->thirdPartyCorrespondentReplyTo = $thirdPartyCorrespondentReplyTo;

        return $this;
    }

    /**
     * Has TertiaryCorrespondent
     *
     * @return bool
     */
    public function hasTertiaryCorrespondent()
    {
        return $this->getThirdPartyCorrespondentForename() ? true : false;
    }

    /**
     * Get ThirdPartyCorrespondentTypeOfRepresentative
     *
     * @return string
     */
    public function getThirdPartyCorrespondentTypeOfRepresentative()
    {
        return $this->thirdPartyCorrespondentTypeOfRepresentative;
    }

    /**
     * Set ThirdPartyCorrespondentTypeOfRepresentative
     *
     * @param string $thirdPartyCorrespondentTypeOfRepresentative
     *
     * @return $this
     */
    public function setThirdPartyCorrespondentTypeOfRepresentative($thirdPartyCorrespondentTypeOfRepresentative)
    {
        $this->thirdPartyCorrespondentTypeOfRepresentative = $thirdPartyCorrespondentTypeOfRepresentative;

        return $this;
    }

    /**
     * Get ThirdPartyCorrespondentConsentAttached
     *
     * @return bool
     */
    public function isThirdPartyCorrespondentConsentAttached()
    {
        return $this->thirdPartyCorrespondentConsentAttached;
    }

    /**
     * Set ThirdPartyCorrespondentConsentAttached
     *
     * @param bool $thirdPartyCorrespondentConsentAttached
     *
     * @return $this
     */
    public function setThirdPartyCorrespondentConsentAttached($thirdPartyCorrespondentConsentAttached)
    {
        $this->thirdPartyCorrespondentConsentAttached = $thirdPartyCorrespondentConsentAttached;

        return $this;
    }
}
