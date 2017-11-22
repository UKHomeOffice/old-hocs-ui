<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\StandardDetails;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\CorrespondentDetails;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\HomeSecretaryReply;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\ReplyToNumberTenCopy;

/**
 * @Assert\Callback(methods={"validate"})
 */
class CtsDcuTreatOfficialCase extends CtsCase
{
    use StandardDetails,
        CorrespondentDetails,
        HomeSecretaryReply,
        ReplyToNumberTenCopy;
 
    /**
     * @var string
     */
    protected $hmpoResponse;

    /**
     * @return string
     */
    public function getCanonicalCorrespondent()
    {
        return $this->getCorrespondentFullName();
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
        return $moreDetailsString;
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
     * Method for validating - email address is required if response is email
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->hmpoResponse == 'Email' && is_null($this->correspondentEmail)) {
            $context->addViolationAt('correspondentEmail', 'You must enter a valid email address.', array(), null);
        }
    }
}
