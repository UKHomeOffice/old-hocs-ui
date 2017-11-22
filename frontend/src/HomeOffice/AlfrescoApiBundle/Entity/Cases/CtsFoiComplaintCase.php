<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\CorrespondentDetails;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CtsFoiComplaintCase
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity\Cases
 */
class CtsFoiComplaintCase extends CtsCase
{
    use CorrespondentDetails;

    /**
     * @var \DateTime $dateReceived
     *
     * @Assert\NotBlank()
     */
    protected $dateReceived;

    /**
     * @var string
     */
    protected $channel;

    /**
     * @var string
     */
    protected $hoCaseOfficer;

    /**
     * @var \DateTime
     */
    protected $responseDate;

    /**
     * @var boolean
     */
    protected $complex;

    /**
     * @var bool
     */
    protected $newInformationReleased;

    /**
     * @var string
     */
    protected $icoReference;

    /**
     * @var string
     */
    protected $icoOutcome;

    /**
     * @var string
     */
    protected $icoComplaintOfficer;

    /**
     * @var \DateTime
     */
    protected $icoOutcomeDate;

    /**
     * @var string
     */
    protected $tsolRep;

    /**
     * @var string
     */
    protected $appellant;

    /**
     * @var bool
     */
    protected $hoJoined;

    /**
     * @var string
     */
    protected $tribunalOutcome;

    /**
     * @var \DateTime
     */
    protected $tribunalOutcomeDate;

    /**
     * @var bool
     */
    protected $enforcementNoticeNeeded;

    /**
     * @var \DateTime
     */
    protected $enforcementNoticeDeadline;

    /**
     * @var string
     */
    protected $organisation;

    /**
     * @var boolean
     */
    protected $foiIsEir;
    
    /**
     * @var string
     */
    protected $exemptions;

    /**
     * @var boolean
     */
    protected $priority;

    /**
     * @var bool
     */
    protected $acpoConsultation;

    /**
     * @var bool
     */
    protected $cabinetOfficeConsultation;

    /**
     * @var bool
     */
    protected $nslgConsultation;

    /**
     * @var bool
     */
    protected $royalsConsultation;

    /**
     * @var bool
     */
    protected $roundRobinAdviceConsultation;

    /**
     * @var bool
     */
    protected $foiMinisterSignOff;

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
     * @return bool
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
     * @return bool
     */
    public function getFoiMinisterSignOff()
    {
        return $this->foiMinisterSignOff;
    }

    /**
     * @param mixed $foiMinisterSignOff
     */
    public function setFoiMinisterSignOff($foiMinisterSignOff)
    {
        $this->foiMinisterSignOff = $this->setBoolean($foiMinisterSignOff);
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
    public function getCabinetOfficeConsultation()
    {
        return $this->cabinetOfficeConsultation == "true" ? true : false;
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
     * @return boolean
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
    public function getFoiIsEir()
    {
        return $this->foiIsEir == "true" ? true : false;
    }

    /**
     * @param string $foiIsEir
     */
    public function setFoiIsEir($foiIsEir)
    {
        $this->foiIsEir = $foiIsEir;
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
     * @return string
     */
    public function getAppellant()
    {
        return $this->appellant;
    }

    /**
     * @param string $appellant
     */
    public function setAppellant($appellant)
    {
        $this->appellant = $appellant;
    }

    /**
     * @return string
     */
    public function getHoCaseOfficer()
    {
        return $this->hoCaseOfficer;
    }

    /**
     * @param string $hoCaseOfficer
     */
    public function setHoCaseOfficer($hoCaseOfficer)
    {
        $this->hoCaseOfficer = $hoCaseOfficer;
    }

    /**
     * @return boolean
     */
    public function getHoJoined()
    {
        return $this->hoJoined == "true" ? true : false;
    }

    /**
     * @param boolean $hoJoined
     */
    public function setHoJoined($hoJoined)
    {
        $this->hoJoined = $hoJoined;
    }

    /**
     * @return string
     */
    public function getIcoComplaintOfficer()
    {
        return $this->icoComplaintOfficer;
    }

    /**
     * @param string $icoComplaintOfficer
     */
    public function setIcoComplaintOfficer($icoComplaintOfficer)
    {
        $this->icoComplaintOfficer = $icoComplaintOfficer;
    }

    /**
     * @return string
     */
    public function getIcoOutcome()
    {
        return $this->icoOutcome;
    }

    /**
     * @param string $icoOutcome
     */
    public function setIcoOutcome($icoOutcome)
    {
        $this->icoOutcome = $icoOutcome;
    }

    /**
     * @return \DateTime
     */
    public function getIcoOutcomeDate()
    {
        return $this->icoOutcomeDate;
    }

    /**
     * @param \DateTime $icoOutcomeDate
     */
    public function setIcoOutcomeDate($icoOutcomeDate)
    {
        $this->icoOutcomeDate = DateHelper::forceDateTimeOrBlank($icoOutcomeDate);
    }

    /**
     * @return string
     */
    public function getIcoReference()
    {
        return $this->icoReference;
    }

    /**
     * @param string $icoReference
     */
    public function setIcoReference($icoReference)
    {
        $this->icoReference = $icoReference;
    }

    /**
     * @return string
     */
    public function getTsolRep()
    {
        return $this->tsolRep;
    }

    /**
     * @param string $tsolRep
     */
    public function setTsolRep($tsolRep)
    {
        $this->tsolRep = $tsolRep;
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
     * @return boolean
     */
    public function getComplex()
    {
        return $this->complex == "true" ? true : false;
    }

    /**
     * @param boolean $complex
     */
    public function setComplex($complex)
    {
        $this->complex = $complex;
    }

    /**
     * @return \DateTime
     */
    public function getDateReceived()
    {
        return $this->dateReceived;
    }

    /**
     * @param \DateTime $dateReceived
     */
    public function setDateReceived($dateReceived)
    {
        $this->dateReceived = DateHelper::forceDateTimeOrBlank($dateReceived);
    }

    /**
     * @return \DateTime
     */
    public function getEnforcementNoticeDeadline()
    {
        return $this->enforcementNoticeDeadline;
    }

    /**
     * @param \DateTime $enforcementNoticeDeadline
     */
    public function setEnforcementNoticeDeadline($enforcementNoticeDeadline)
    {
        $this->enforcementNoticeDeadline = DateHelper::forceDateTimeOrBlank($enforcementNoticeDeadline);
    }

    /**
     * @return boolean
     */
    public function getEnforcementNoticeNeeded()
    {
        return $this->enforcementNoticeNeeded == "true" ? true : false;
    }

    /**
     * @param boolean $enforcementNoticeNeeded
     */
    public function setEnforcementNoticeNeeded($enforcementNoticeNeeded)
    {
        $this->enforcementNoticeNeeded = $enforcementNoticeNeeded;
    }

    /**
     * @return boolean
     */
    public function getNewInformationReleased()
    {
        return $this->newInformationReleased == "true" ? true : false;
    }

    /**
     * @param boolean $newInformationReleased
     */
    public function setNewInformationReleased($newInformationReleased)
    {
        $this->newInformationReleased = $newInformationReleased;
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
     * @return string
     */
    public function getTribunalOutcome()
    {
        return $this->tribunalOutcome;
    }

    /**
     * @param string $tribunalOutcome
     */
    public function setTribunalOutcome($tribunalOutcome)
    {
        $this->tribunalOutcome = $tribunalOutcome;
    }

    /**
     * @return \DateTime
     */
    public function getTribunalOutcomeDate()
    {
        return $this->tribunalOutcomeDate;
    }

    /**
     * @param \DateTime $tribunalOutcomeDate
     */
    public function setTribunalOutcomeDate($tribunalOutcomeDate)
    {
        $this->tribunalOutcomeDate = DateHelper::forceDateTimeOrBlank($tribunalOutcomeDate);
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
     * @return string
     */
    public function getCanonicalCorrespondent()
    {
        return $this->getCorrespondentFullName();
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
     * Method for generating the text to go into the 'More details' column of search
     * @return string
     */
    public function generateMoreDetailsColumnForSearch()
    {
        $moreDetailsString = '';
        if ($this->hoCaseOfficer != null) {
            $moreDetailsString .= '<div><label>HO case officer: </label><span>'.$this->hoCaseOfficer.'</span></div>';
        }
        if ($this->organisation != null) {
            $moreDetailsString .= '<div><label>Organisation: </label><span>'.$this->organisation.'</span></div>';
        }
        switch ($this->correspondenceType) {
            case 'FTCI':
                if ($this->icoReference != null) {
                    $moreDetailsString .= '<div><label>ICO ref: </label><span>'.$this->icoReference.'</span></div>';
                }
                break;
            case 'FSC':
                $complex = $this->getComplex() ? 'Yes' : 'No';
                $moreDetailsString .= '<div><label>Complex: </label><span>'.$complex.'</span></div>';
                break;
            case 'FSCI':
                if ($this->icoReference != null) {
                    $moreDetailsString .= '<div><label>ICO ref: </label><span>'.$this->icoReference.'</span></div>';
                }
                if ($this->icoComplaintOfficer != null) {
                    //@codingStandardsIgnoreStart
                    $moreDetailsString .= '<div><label>ICO complaint officer: </label><span>'.$this->icoComplaintOfficer.'</span></div>';
                    //@codingStandardsIgnoreEnd
                }
                if ($this->icoOutcome != null) {
                    $moreDetailsString .= '<div><label>ICO outcome: </label><span>'.$this->icoOutcome.'</span></div>';
                }
                break;
            case 'FLT':
            case 'FUT':
                if ($this->tribunalOutcome != null) {
                    //@codingStandardsIgnoreStart
                    $moreDetailsString .= '<div><label>Tribunal outcome: </label><span>'.$this->tribunalOutcome.'</span></div>';
                    //@codingStandardsIgnoreEnd
                }
                if ($this->tsolRep != null) {
                    $moreDetailsString .= '<div><label>TSOL rep: </label><span>'.$this->tsolRep.'</span></div>';
                }
                break;
        }
        return $moreDetailsString;
    }


    /**
     * Return a comma separated list of consultations for use in view case template.
     *
     * @return string
     */
    public function getConsultations()
    {
        $consultationArray = [];
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
}
