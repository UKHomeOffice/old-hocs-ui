<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\CaseComponents;

/**
 * Class SecondaryCorrespondent
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity\CaseComponents
 */
trait SecondaryCorrespondent
{
    /**
     * @var string
     */
    private $secondaryTypeOfCorrespondent;

    /**
     * @var string
     */
    private $secondaryCorrespondentTitle;

    /**
     * @var string
     */
    private $secondaryCorrespondentForename;

    /**
     * @var string
     */
    private $secondaryCorrespondentSurname;

    /**
     * @var string
     */
    private $secondaryCorrespondentAddressLine1;

    /**
     * @var string
     */
    private $secondaryCorrespondentAddressLine2;

    /**
     * @var string
     */
    private $secondaryCorrespondentAddressLine3;

    /**
     * @var string
     */
    private $secondaryCorrespondentPostcode;

    /**
     * @var string
     */
    private $secondaryCorrespondentCountry;

    /**
     * @var string
     */
    private $secondaryCorrespondentTelephone;

    /**
     * @var string
     */
    private $secondaryCorrespondentEmail;

    /**
     *
     * @var boolean
     */
    private $secondaryCorrespondentReplyTo;

    /**
     * @var string
     */
    private $secondaryCorrespondentTypeOfRepresentative;

    /**
     * @var boolean
     */
    private $secondaryCorrespondentConsentAttached;

    /**
     * Get SecondaryTypeOfCorrespondent
     *
     * @return string
     */
    public function getSecondaryTypeOfCorrespondent()
    {
        return $this->secondaryTypeOfCorrespondent;
    }

    /**
     * Set SecondaryTypeOfCorrespondent
     *
     * @param string $secondaryTypeOfCorrespondent
     *
     * @return static
     */
    public function setSecondaryTypeOfCorrespondent($secondaryTypeOfCorrespondent)
    {
        $this->secondaryTypeOfCorrespondent = $secondaryTypeOfCorrespondent;

        return $this;
    }

    /**
     * Get SecondaryCorrespondentTitle
     *
     * @return string
     */
    public function getSecondaryCorrespondentTitle()
    {
        return $this->secondaryCorrespondentTitle;
    }

    /**
     * Set SecondaryCorrespondentTitle
     *
     * @param string $secondaryCorrespondentTitle
     *
     * @return static
     */
    public function setSecondaryCorrespondentTitle($secondaryCorrespondentTitle)
    {
        $this->secondaryCorrespondentTitle = $secondaryCorrespondentTitle;

        return $this;
    }

    /**
     * Get SecondaryCorrespondentForename
     *
     * @return string
     */
    public function getSecondaryCorrespondentForename()
    {
        return $this->secondaryCorrespondentForename;
    }

    /**
     * Set SecondaryCorrespondentForename
     *
     * @param string $secondaryCorrespondentForename
     *
     * @return static
     */
    public function setSecondaryCorrespondentForename($secondaryCorrespondentForename)
    {
        $this->secondaryCorrespondentForename = $secondaryCorrespondentForename;

        return $this;
    }

    /**
     * Get SecondaryCorrespondentSurname
     *
     * @return string
     */
    public function getSecondaryCorrespondentSurname()
    {
        return $this->secondaryCorrespondentSurname;
    }

    /**
     * Set SecondaryCorrespondentSurname
     *
     * @param string $secondaryCorrespondentSurname
     *
     * @return static
     */
    public function setSecondaryCorrespondentSurname($secondaryCorrespondentSurname)
    {
        $this->secondaryCorrespondentSurname = $secondaryCorrespondentSurname;

        return $this;
    }

    /**
     * Get SecondaryCorrespondentAddressLine1
     *
     * @return string
     */
    public function getSecondaryCorrespondentAddressLine1()
    {
        return $this->secondaryCorrespondentAddressLine1;
    }

    /**
     * Set SecondaryCorrespondentAddressLine1
     *
     * @param string $secondaryCorrespondentAddressLine1
     *
     * @return static
     */
    public function setSecondaryCorrespondentAddressLine1($secondaryCorrespondentAddressLine1)
    {
        $this->secondaryCorrespondentAddressLine1 = $secondaryCorrespondentAddressLine1;

        return $this;
    }

    /**
     * Get SecondaryCorrespondentAddressLine2
     *
     * @return string
     */
    public function getSecondaryCorrespondentAddressLine2()
    {
        return $this->secondaryCorrespondentAddressLine2;
    }

    /**
     * Set SecondaryCorrespondentAddressLine2
     *
     * @param string $secondaryCorrespondentAddressLine2
     *
     * @return static
     */
    public function setSecondaryCorrespondentAddressLine2($secondaryCorrespondentAddressLine2)
    {
        $this->secondaryCorrespondentAddressLine2 = $secondaryCorrespondentAddressLine2;

        return $this;
    }

    /**
     * Get SecondaryCorrespondentAddressLine3
     *
     * @return string
     */
    public function getSecondaryCorrespondentAddressLine3()
    {
        return $this->secondaryCorrespondentAddressLine3;
    }

    /**
     * Set SecondaryCorrespondentAddressLine3
     *
     * @param string $secondaryCorrespondentAddressLine3
     *
     * @return static
     */
    public function setSecondaryCorrespondentAddressLine3($secondaryCorrespondentAddressLine3)
    {
        $this->secondaryCorrespondentAddressLine3 = $secondaryCorrespondentAddressLine3;

        return $this;
    }

    /**
     * Get SecondaryCorrespondentPostcode
     *
     * @return string
     */
    public function getSecondaryCorrespondentPostcode()
    {
        return $this->secondaryCorrespondentPostcode;
    }

    /**
     * Set SecondaryCorrespondentPostcode
     *
     * @param string $secondaryCorrespondentPostcode
     *
     * @return static
     */
    public function setSecondaryCorrespondentPostcode($secondaryCorrespondentPostcode)
    {
        $this->secondaryCorrespondentPostcode = $secondaryCorrespondentPostcode;

        return $this;
    }

    /**
     * Get SecondaryCorrespondentCountry
     *
     * @return string
     */
    public function getSecondaryCorrespondentCountry()
    {
        return $this->secondaryCorrespondentCountry;
    }

    /**
     * Set SecondaryCorrespondentCountry
     *
     * @param string $secondaryCorrespondentCountry
     *
     * @return static
     */
    public function setSecondaryCorrespondentCountry($secondaryCorrespondentCountry)
    {
        $this->secondaryCorrespondentCountry = $secondaryCorrespondentCountry;

        return $this;
    }

    /**
     * Get SecondaryCorrespondentTelephone
     *
     * @return string
     */
    public function getSecondaryCorrespondentTelephone()
    {
        return $this->secondaryCorrespondentTelephone;
    }

    /**
     * Set SecondaryCorrespondentTelephone
     *
     * @param string $secondaryCorrespondentTelephone
     *
     * @return static
     */
    public function setSecondaryCorrespondentTelephone($secondaryCorrespondentTelephone)
    {
        $this->secondaryCorrespondentTelephone = $secondaryCorrespondentTelephone;

        return $this;
    }

    /**
     * Get SecondaryCorrespondentEmail
     *
     * @return string
     */
    public function getSecondaryCorrespondentEmail()
    {
        return $this->secondaryCorrespondentEmail;
    }

    /**
     * Set SecondaryCorrespondentEmail
     *
     * @param string $secondaryCorrespondentEmail
     *
     * @return static
     */
    public function setSecondaryCorrespondentEmail($secondaryCorrespondentEmail)
    {
        $this->secondaryCorrespondentEmail = $secondaryCorrespondentEmail;

        return $this;
    }

    /**
     * Get SecondaryCorrespondentReplyTo
     *
     * @return bool
     */
    public function isSecondaryCorrespondentReplyTo()
    {
        return $this->secondaryCorrespondentReplyTo;
    }

    /**
     * Set SecondaryCorrespondentReplyTo
     *
     * @param bool $secondaryCorrespondentReplyTo
     *
     * @return static
     */
    public function setSecondaryCorrespondentReplyTo($secondaryCorrespondentReplyTo)
    {
        $this->secondaryCorrespondentReplyTo = $secondaryCorrespondentReplyTo;

        return $this;
    }

    /**
     * Has TertiaryCorrespondent
     *
     * @return bool
     */
    public function hasSecondaryCorrespondent()
    {
        return $this->getSecondaryCorrespondentForename() ? true : false;
    }

    /**
     * Get SecondaryCorrespondentTypeOfRepresentative
     *
     * @return string
     */
    public function getSecondaryCorrespondentTypeOfRepresentative()
    {
        return $this->secondaryCorrespondentTypeOfRepresentative;
    }

    /**
     * Set SecondaryCorrespondentTypeOfRepresentative
     *
     * @param string $secondaryCorrespondentTypeOfRepresentative
     *
     * @return $this
     */
    public function setSecondaryCorrespondentTypeOfRepresentative($secondaryCorrespondentTypeOfRepresentative)
    {
        $this->secondaryCorrespondentTypeOfRepresentative = $secondaryCorrespondentTypeOfRepresentative;

        return $this;
    }

    /**
     * Get SecondaryCorrespondentConsentAttached
     *
     * @return bool
     */
    public function isSecondaryCorrespondentConsentAttached()
    {
        return $this->secondaryCorrespondentConsentAttached;
    }

    /**
     * Set SecondaryCorrespondentConsentAttached
     *
     * @param bool $secondaryCorrespondentConsentAttached
     *
     * @return $this
     */
    public function setSecondaryCorrespondentConsentAttached($secondaryCorrespondentConsentAttached)
    {
        $this->secondaryCorrespondentConsentAttached = $secondaryCorrespondentConsentAttached;
        return $this;
    }
}
