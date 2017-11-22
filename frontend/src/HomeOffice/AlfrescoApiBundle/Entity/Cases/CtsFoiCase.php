<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\CorrespondentDetails;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CtsFoiCase
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity\Cases
 */
class CtsFoiCase extends CtsCase
{
    use CorrespondentDetails;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     */
    protected $dateReceived;

    /**
     * @var string
     */
    protected $hoCaseOfficer;

    /**
     * @var array
     */
    protected $appeals;

    /**
     * @var \DateTime
     */
    protected $allocateTarget;

    /**
     * @var \DateTime
     */
    protected $draftResponseTarget;

    /**
     * @var \DateTime
     */
    protected $scsApprovalTarget;

    /**
     * @var \DateTime
     */
    protected $finalApprovalTarget;

    /**
     * @var bool
     */
    protected $foiIsEir;

    /**
     * @var string
     */
    protected $exemptions;

    /**
     * @var bool
     */
    protected $pitExtension;

    /**
     * @var \DateTime
     */
    protected $pitLetterSentDate;

    /**
     * @var string
     */
    protected $pitQualifiedExemptions;

    /**
     * @var bool
     */
    protected $foiDisclosure;

    /**
     * @var bool
     */
    protected $acpoConsultation;

    /**
     *
     * @var bool
     */
    protected $nslgConsultation;

    /**
     *
     * @var bool
     */
    protected $royalsConsultation;

    /**
     *
     * @var bool
     */
    protected $roundRobinAdviceConsultation;

    /**
     * @var string
     */
    protected $organisation;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Select the original channel", groups={"Case_Create"})
     */
    protected $channel;

    /**
     * @var bool
     */
    protected $priority;

    /**
     * @var string
     */
    protected $hmpoResponse;

    /**
     * @var \DateTime
     */
    protected $responseDate;

    /**
     * @var bool
     */
    protected $foiMinisterSignOff;

    /**
     * @var bool
     */
    protected $cabinetOfficeConsultation;

    /**
     * @var string
     */
    protected $answeringMinister;

    /**
     * @return string
     */
    public function getAnsweringMinister()
    {
        return $this->answeringMinister;
    }

    /**
     * @param string $answeringMinister
     */
    public function setAnsweringMinister($answeringMinister)
    {
        $this->answeringMinister = $answeringMinister;
    }

    /**
     * @return \DateTime
     */
    public function getResponseDate()
    {
        return $this->responseDate;
    }

    /**
     * @param \DateTime $responseDate
     */
    public function setResponseDate($responseDate)
    {
        $this->responseDate = DateHelper::forceDateTimeOrBlank($responseDate);
    }

    /**
     * @param string $response
     *
     * @return CtsFoiCase
     */
    public function setHmpoResponse($response)
    {
        $this->hmpoResponse = $response;

        return $this;
    }

    /**
     * @return string
     */
    public function getHmpoResponse()
    {
        return $this->hmpoResponse;
    }

    /**
     * @return bool
     */
    public function getPriority()
    {
        return $this->priority == "true" ? true : false;
    }

    /**
     * @param string $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
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
     * @return \DateTime
     */
    public function getDateReceived()
    {
        return $this->dateReceived;
    }

    /**
     * @param string $dateReceived
     */
    public function setDateReceived($dateReceived)
    {
        $this->dateReceived = DateHelper::forceDateTimeOrBlank($dateReceived);
    }

    /**
     * @return string
     */
    public function getFoiMinisterSignOff()
    {
        return $this->foiMinisterSignOff;
    }

    /**
     * @param string|bool $foiMinisterSignOff
     */
    public function setFoiMinisterSignOff($foiMinisterSignOff)
    {
        $this->foiMinisterSignOff = $this->setBoolean($foiMinisterSignOff);
    }

    /**
     * @return bool
     */
    public function getFoiIsEir()
    {
        return $this->foiIsEir;
    }

    /**
     * @param string|bool $foiIsEir
     */
    public function setFoiIsEir($foiIsEir)
    {
        $this->foiIsEir = $this->setBoolean($foiIsEir);
    }

    /**
     * @return array
     */
    public function getExemptions()
    {
        if (!is_array($this->exemptions)) {
            $exemptionsArray = [];
            foreach (explode(',', $this->exemptions) as $exemption) {
                $exemptionsArray[$exemption] = $exemption;
            }
            return $exemptionsArray;
        }

        return $this->exemptions;
    }

    /**
     * @return string
     */
    public function getExemptionsString()
    {
        if (is_array($this->exemptions)) {
            return implode(', ', $this->exemptions);
        }

        return $this->exemptions;
    }

    /**
     * @param string $exemptions
     */
    public function setExemptions($exemptions)
    {
        $this->exemptions = $exemptions;
    }

    /**
     * @return array
     */
    public function getAppeals()
    {
        return $this->appeals;
    }

    /**
     * @param array $appeals
     */
    public function setAppeals($appeals)
    {
        $this->appeals = $appeals;
    }

    /**
     * @return \DateTime
     */
    public function getAllocateTarget()
    {
        return $this->allocateTarget;
    }

    /**
     * @param \DateTime $allocateTarget
     */
    public function setAllocateTarget($allocateTarget)
    {
        $this->allocateTarget = DateHelper::forceDateTimeOrBlank($allocateTarget);
    }

    /**
     * @return \DateTime
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
     * @return \DateTime
     */
    public function getScsApprovalTarget()
    {
        return $this->scsApprovalTarget;
    }

    /**
     * @param DateTime $scsApprovalTarget
     */
    public function setScsApprovalTarget($scsApprovalTarget)
    {
        $this->scsApprovalTarget = DateHelper::forceDateTimeOrBlank($scsApprovalTarget);
    }

    /**
     * @return \DateTime
     */
    public function getFinalApprovalTarget()
    {
        return $this->finalApprovalTarget;
    }

    /**
     * @param DateTime $finalApprovalTarget
     */
    public function setFinalApprovalTarget($finalApprovalTarget)
    {
        $this->finalApprovalTarget = DateHelper::forceDateTimeOrBlank($finalApprovalTarget);
    }

    /**
     * @return string
     */
    public function getPitExtension()
    {
        return $this->pitExtension ? true : false;
    }

    /**
     * @param string $pitExtension
     */
    public function setPitExtension($pitExtension)
    {
        $this->pitExtension = $this->setBoolean($pitExtension);
    }

    /**
     * @return \DateTime
     */
    public function getPitLetterSentDate()
    {
        return $this->pitLetterSentDate;
    }

    /**
     * @param \DateTime $pitLetterSentDate
     */
    public function setPitLetterSentDate($pitLetterSentDate)
    {
        $this->pitLetterSentDate = DateHelper::forceDateTimeOrBlank($pitLetterSentDate);
    }

    /**
     *
     * @return array
     */
    public function getPitQualifiedExemptions()
    {
        if (!is_array($this->pitQualifiedExemptions)) {
            $pitQualifiedExemptionsArray = array();
            foreach (explode(',', $this->pitQualifiedExemptions) as $exemption) {
                $pitQualifiedExemptionsArray[$exemption] = $exemption;
            }

            return $pitQualifiedExemptionsArray;
        }

        return $this->pitQualifiedExemptions;
    }

    /**
     * @return string
     */
    public function getPitQualifiedExemptionsString()
    {
        if (is_array($this->pitQualifiedExemptions)) {
            return implode(', ', $this->pitQualifiedExemptions);
        }

        return $this->pitQualifiedExemptions;
    }

    /**
     *
     * @param string $pitQualifiedExemptions
     */
    public function setPitQualifiedExemptions($pitQualifiedExemptions)
    {
        $this->pitQualifiedExemptions = $pitQualifiedExemptions;
    }

    /**
     *
     * @return boolean
     */
    public function getAcpoConsultation()
    {
        return $this->acpoConsultation == "true" ? true : false;
    }

    /**
     *
     * @return boolean
     */
    public function getNslgConsultation()
    {
        return $this->nslgConsultation == "true" ? true : false;
    }

    /**
     *
     * @return boolean
     */
    public function getRoyalsConsultation()
    {
        return $this->royalsConsultation == "true" ? true : false;
    }

    /**
     *
     * @return boolean
     */
    public function getRoundRobinAdviceConsultation()
    {
        return $this->roundRobinAdviceConsultation == "true" ? true : false;
    }

    /**
     *
     * @param boolean $acpoConsultation
     */
    public function setAcpoConsultation($acpoConsultation)
    {
        $this->acpoConsultation = $acpoConsultation;
    }

    /**
     *
     * @param boolean $cabinetOfficeConsultation
     */
    public function setCabinetOfficeConsultation($cabinetOfficeConsultation)
    {
        $this->cabinetOfficeConsultation = $cabinetOfficeConsultation;
    }

    /**
     *
     * @param boolean $nslgConsultation
     */
    public function setNslgConsultation($nslgConsultation)
    {
        $this->nslgConsultation = $nslgConsultation;
    }

    /**
     *
     * @return boolean
     */
    public function getCabinetOfficeConsultation()
    {
        return $this->cabinetOfficeConsultation == "true" ? true : false;
    }

    /**
     *
     * @param boolean $royalsConsultation
     */
    public function setRoyalsConsultation($royalsConsultation)
    {
        $this->royalsConsultation = $royalsConsultation;
    }

    /**
     *
     * @param boolean $roundRobinAdviceConsultation
     */
    public function setRoundRobinAdviceConsultation($roundRobinAdviceConsultation)
    {
        $this->roundRobinAdviceConsultation = $roundRobinAdviceConsultation;
    }

    /**
     * Return a comma separated list of consultations for use in view case template.
     * @return string
     */
    public function getConsultations()
    {
        $consultationArray = array();
        if ($this->getAcpoConsultation()) {
            array_push($consultationArray, 'ACPO');
        }
        if ($this->getCabinetOfficeConsultation()) {
            array_push($consultationArray, 'Cabinet Office');
        }
        if ($this->getNslgConsultation()) {
            array_push($consultationArray, 'NSLG');
        }
        if ($this->getRoyalsConsultation()) {
            array_push($consultationArray, 'Royals');
        }
        if ($this->getRoundRobinAdviceConsultation()) {
            array_push($consultationArray, 'Round robin advice');
        }

        if (count($consultationArray) == 0) {
            return 'None';
        }

        return implode(', ', $consultationArray);
    }

    /**
     * @return string
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @param string $organisation
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     *
     * @return boolean
     */
    public function getFoiDisclosure()
    {
        return $this->foiDisclosure == "true" ? true : false;
    }

    /**
     *
     * @param boolean $foiDisclosure
     */
    public function setFoiDisclosure($foiDisclosure)
    {
        $this->foiDisclosure = $foiDisclosure;
    }

    /**
     * @return string
     */
    public function getCanonicalCorrespondent()
    {
        return $this->getCorrespondentFullName();
    }

    /**
     *
     * @param string $hoCaseOfficer
     */
    public function setHoCaseOfficer($hoCaseOfficer)
    {
        $this->hoCaseOfficer = $hoCaseOfficer;
    }

    /**
     * @return string
     */
    public function getHoCaseOfficer()
    {
        return $this->hoCaseOfficer;
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
        $foiMinisterSignOff = $this->getFoiMinisterSignOff() ? 'Yes' : 'No';
        $moreDetailsString .= '<div><label>Min. sign-off: </label><span>'.$foiMinisterSignOff.'</span></div>';

        return $moreDetailsString;
    }
}
