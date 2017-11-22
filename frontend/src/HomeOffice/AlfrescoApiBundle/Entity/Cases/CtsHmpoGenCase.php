<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\CorrespondentDetails;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\HmpoStandardDetails;

class CtsHmpoGenCase extends CtsCase
{
    use CorrespondentDetails, HmpoStandardDetails;
 
    /**
     *
     * @var string
     */
    private $typeOfThirdParty;
 
    /**
     *
     * @var boolean
     */
    private $consentAttached;
 
    /**
     * @return boolean
     */
    public function getConsentAttached()
    {
        return $this->consentAttached == "true" ? true : false;
    }

    /**
     * @param boolean $consentAttached
     */
    public function setConsentAttached($consentAttached)
    {
        $this->consentAttached = $consentAttached;
    }
 
    /**
     * @return string
     */
    public function getTypeOfThirdParty()
    {
        return $this->typeOfThirdParty;
    }
 
    /**
     *
     * @param boolean $typeOfThirdParty
     */
    public function setTypeOfThirdParty($typeOfThirdParty)
    {
        $this->typeOfThirdParty = $typeOfThirdParty;
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
