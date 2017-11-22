<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\CaseComponents;

use Symfony\Component\Validator\Constraints as Assert;

trait CorrespondentDetails
{
    /**
     * @var string
     */
    protected $correspondentTitle;

    /**
     * @var string
     */
    protected $correspondentForename;

    /**
     * @var string
     */
    protected $correspondentSurname;

    /**
     * @var string
     */
    protected $correspondentAddressLine1;

    /**
     * @var string
     */
    protected $correspondentAddressLine2;

    /**
     * @var string
     */
    protected $correspondentAddressLine3;

    /**
     * @var string
     */
    protected $correspondentPostcode;

    /**
     * @var string
     */
    protected $correspondentCountry;

    /**
     * @var string
     */
    protected $correspondentTelephone;

    /**
     * @var string
     */
    protected $correspondentEmail;

    /**
     * @return string
     */
    public function getCorrespondentTitle()
    {
        return $this->correspondentTitle;
    }

    /**
     * @param string $correspondentTitle
     */
    public function setCorrespondentTitle($correspondentTitle)
    {
        $this->correspondentTitle = $correspondentTitle;
    }

    /**
     * @return string
     */
    public function getCorrespondentForename()
    {
        return $this->correspondentForename;
    }

    /**
     * @param string $correspondentForename
     */
    public function setCorrespondentForename($correspondentForename)
    {
        $this->correspondentForename = $correspondentForename;
    }

    /**
     * @return string
     */
    public function getCorrespondentSurname()
    {
        return $this->correspondentSurname;
    }

    /**
     * @param string $correspondentSurname
     */
    public function setCorrespondentSurname($correspondentSurname)
    {
        $this->correspondentSurname = $correspondentSurname;
    }
 
    /**
     *
     * @return string
     */
    public function getCorrespondentFullName()
    {
        $fullName = null;
        if ($this->correspondentForename != null) {
            $fullName .= $this->correspondentForename . ' ';
        }
        if ($this->correspondentSurname != null) {
            $fullName .= $this->correspondentSurname;
        }
        return $fullName;
    }

    /**
     * @return string
     */
    public function getCorrespondentAddressLine1()
    {
        return $this->correspondentAddressLine1;
    }

    /**
     * @param string $correspondentAddressLine1
     */
    public function setCorrespondentAddressLine1($correspondentAddressLine1)
    {
        $this->correspondentAddressLine1 = $correspondentAddressLine1;
    }

    /**
     * @return string
     */
    public function getCorrespondentAddressLine2()
    {
        return $this->correspondentAddressLine2;
    }

    /**
     * @param string $correspondentAddressLine2
     */
    public function setCorrespondentAddressLine2($correspondentAddressLine2)
    {
        $this->correspondentAddressLine2 = $correspondentAddressLine2;
    }

    /**
     * @return string
     */
    public function getCorrespondentAddressLine3()
    {
        return $this->correspondentAddressLine3;
    }

    /**
     * @param string $correspondentAddressLine3
     */
    public function setCorrespondentAddressLine3($correspondentAddressLine3)
    {
        $this->correspondentAddressLine3 = $correspondentAddressLine3;
    }

    /**
     * @return string
     */
    public function getCorrespondentCountry()
    {
        return $this->correspondentCountry;
    }

    /**
     * @param string $correspondentCountry
     */
    public function setCorrespondentCountry($correspondentCountry)
    {
        $this->correspondentCountry = $correspondentCountry;
    }

    /**
     * Get Correspondent Address
     *
     * @param string $delimiter
     *
     * @return string
     */
    public function getCorrespondentAddress($delimiter = ', ')
    {
        return
            ($this->getCorrespondentAddressLine1() ? $this->getCorrespondentAddressLine1() . $delimiter : null).
            ($this->getCorrespondentAddressLine2() ? $this->getCorrespondentAddressLine2() . $delimiter : null).
            ($this->getCorrespondentAddressLine3() ? $this->getCorrespondentAddressLine3() . $delimiter : null).
            ($this->getCorrespondentPostcode() ?: null);
    }

    /**
     * @return string
     */
    public function getCorrespondentEmail()
    {
        return $this->correspondentEmail;
    }

    /**
     * @param string $correspondentEmail
     */
    public function setCorrespondentEmail($correspondentEmail)
    {
        $this->correspondentEmail = $correspondentEmail;
    }

    /**
     * @return string
     */
    public function getCorrespondentPostcode()
    {
        return $this->correspondentPostcode;
    }

    /**
     * @param string $correspondentPostcode
     */
    public function setCorrespondentPostcode($correspondentPostcode)
    {
        $this->correspondentPostcode = $correspondentPostcode;
    }

    /**
     * @return string
     */
    public function getCorrespondentTelephone()
    {
        return $this->correspondentTelephone;
    }

    /**
     * @param string $correspondentTelephone
     */
    public function setCorrespondentTelephone($correspondentTelephone)
    {
        $this->correspondentTelephone = $correspondentTelephone;
    }

    /**
     * @return string
     */
    public function getCorrespondentFullAddress()
    {
        $address  = $this->getCorrespondentAddressLine1() ? $this->getCorrespondentAddressLine1().'<br/>' : '';
        $address .= $this->getCorrespondentAddressLine2() ? $this->getCorrespondentAddressLine2().'<br/>' : '';
        $address .= $this->getCorrespondentAddressLine3() ? $this->getCorrespondentAddressLine3().'<br/>' : '';
        $address .= $this->getCorrespondentPostcode() ? $this->getCorrespondentPostcode() : '';

        return $address;
    }
}
