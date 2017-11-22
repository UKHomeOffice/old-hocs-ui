<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\Cases;

use DateTime;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\ReplyToMember;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CtsPqCase
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity\Cases
 */
class CtsPqCase extends CtsCase
{
    use ReplyToMember;

    /**
     * @var string
     */
    protected $uin;

    /**
     * @var DateTime $opDate
     *
     * @Assert\NotBlank()
     */
    protected $opDate;

    /**
     * @var DateTime
     */
    protected $woDate;

    /**
     * @var string
     */
    protected $questionNumber;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Case_Create"}, message="A question is required")
     */
    protected $questionText;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Case_Draft"}, message="A response is required")
     */
    protected $answerText;

    /**
     * @var string
     */
    protected $receivedType = 'Direct';

    /**
     * @var DateTime
     */
    protected $draftResponseTarget;

    /**
     * @var string
     */
    protected $constituency;

    /**
     * @var string
     */
    protected $party;

    /**
     * @var boolean
     */
    protected $signedByHomeSec;

    /**
     * @deprecated unused parameter in alfresco since 05/2015
     * @var boolean
     */
    protected $signedByLordsMinister;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Case_ParlyApproval_Lords"}, message="Select a Lord's minister")
     */
    protected $lordsMinister;

    /**
     * @var boolean
     */
    protected $reviewedByPermSec;

    /**
     * @var boolean
     *
     * This was requested to default to true by the PQ team. 
     * Don't change this without review/notice.
     */
    protected $reviewedBySpads = true;

    /**
     * @var boolean
     */
    protected $roundRobin;

    /**
     * @var string
     */
    protected $cabinetOfficeGuidance;

    /**
     * @var string
     */
    protected $transferDepartmentName;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Case_Grouped"}, message="Select another case to group with")
     */
    protected $uinsToGroup;

    /**
     * @var array
     */
    protected $groupedCases = [];

    /**
     *
     * @var boolean
     */
    protected $isGroupedMaster;

    /**
     *
     * @var boolean
     */
    protected $isGroupedSlave;

    /**
     *
     * @var string
     */
    protected $masterNodeRef;

    /**
     *
     * @var string
     *
     * @Assert\NotBlank(groups={"Case_Dispatch"}, message="Select an answering minister")
     */
    protected $answeringMinister;

    /**
     *
     * @var string
     */
    protected $answeringMinisterId;

    /**
     *
     * @return string
     */
    public function getUin()
    {
        return $this->uin;
    }

    public function getOpDate()
    {
        return $this->opDate;
    }

    public function getWoDate()
    {
        return $this->woDate;
    }

    /**
     *
     * @return string
     */
    public function getQuestionNumber()
    {
        return $this->questionNumber;
    }

    /**
     *
     * @return string
     */
    public function getQuestionText()
    {
        return $this->questionText;
    }

    public function getReceivedType()
    {
        return $this->receivedType;
    }

    /**
     * @return DateTime
     */
    public function getDraftResponseTarget()
    {
        return $this->draftResponseTarget;
    }

    public function getConstituency()
    {
        return $this->constituency;
    }

    public function getParty()
    {
        return $this->party;
    }

    public function setUin($uin)
    {
        $this->uin = $uin;
    }

    public function setOpDate($opDate)
    {
        $this->opDate = DateHelper::forceDateTimeOrBlank($opDate);
    }

    public function setWoDate($woDate)
    {
        $this->woDate = DateHelper::forceDateTimeOrBlank($woDate);
    }

    public function setQuestionNumber($questionNumber)
    {
        $this->questionNumber = $questionNumber;
    }

    public function setQuestionText($questionText)
    {
        $this->questionText = $questionText;
    }

    public function setReceivedType($receivedType)
    {
        $this->receivedType = $receivedType;
    }

    /**
     * @param DateTime $draftResponseTarget
     */
    public function setDraftResponseTarget($draftResponseTarget)
    {
        $this->draftResponseTarget = DateHelper::forceDateTimeOrBlank($draftResponseTarget);
    }

    public function setConstituency($constituency)
    {
        $this->constituency = $constituency;
    }

    public function setParty($party)
    {
        $this->party = $party;
    }

    public function setSignedByHomeSec($signedByHomeSec)
    {
        $this->signedByHomeSec = $signedByHomeSec;
    }

    public function setReviewedByPermSec($reviewedByPermSec)
    {
        $this->reviewedByPermSec = $reviewedByPermSec;
    }

    public function setSignedByLordsMinister($signedByLordsMinister)
    {
        $this->signedByLordsMinister = $signedByLordsMinister;
    }

    public function setReviewedBySpads($reviewedBySpads)
    {
        $this->reviewedBySpads = $reviewedBySpads;
    }

    public function getSignedByHomeSec()
    {
        return $this->signedByHomeSec == "true" ? true : false;
    }

    public function getReviewedByPermSec()
    {
        return $this->reviewedByPermSec == "true" ? true : false;
    }

    public function getSignedByLordsMinister()
    {
        return $this->signedByLordsMinister == "true" ? true : false;
    }

    public function getReviewedBySpads()
    {
        if ($this->getSignedByHomeSec() && $this->getReviewedByPermSec()) {
            // Always require review by spads when Home Sec and Perm Sec is true
            return true;
        }

        return $this->reviewedBySpads == "true" ? true : false;
    }

    /**
     * Does the case require review?
     *
     * @return bool
     */
    public function isReviewRequired()
    {
        return $this->getSignedByHomeSec() || $this->getReviewedByPermSec() || $this->getReviewedBySpads();
    }

    public function getRoundRobin()
    {
        return (boolean) $this->roundRobin;
    }

    public function setRoundRobin($roundRobin)
    {
        $this->roundRobin = $roundRobin;
    }

    public function getCabinetOfficeGuidance()
    {
        return $this->cabinetOfficeGuidance;
    }

    public function setCabinetOfficeGuidance($cabinetOfficeGuidance)
    {
        $this->cabinetOfficeGuidance = $cabinetOfficeGuidance;
    }

    public function getTransferDepartmentName()
    {
        return $this->transferDepartmentName;
    }

    public function setTransferDepartmentName($transferDepartmentName)
    {
        $this->transferDepartmentName = $transferDepartmentName;
    }

    public function getAnswerText()
    {
        return $this->answerText;
    }

    public function setAnswerText($answerText)
    {
        $this->answerText = $answerText;
    }

    public function setUinsToGroup($uinsToGroup)
    {
        $this->uinsToGroup = $uinsToGroup;
    }

    public function getUinsToGroup()
    {
        return $this->uinsToGroup;
    }

    /**
     * @return CtsPqCase[]
     */
    public function getGroupedCases()
    {
        return $this->groupedCases;
    }

    /**
     * Must be passed an array of CtsPqCase objects.
     * @param array $groupedCases
     */
    public function setGroupedCases(array $groupedCases)
    {
        $this->groupedCases = $groupedCases;
    }

    /**
     *
     * @return boolean
     */
    public function getIsGroupedMaster()
    {
        return $this->isGroupedMaster == "true" ? true : false;
    }

    /**
     *
     * @param string $isGroupedMaster
     */
    public function setIsGroupedMaster($isGroupedMaster)
    {
        $this->isGroupedMaster = $isGroupedMaster;
    }

    /**
     *
     * @return boolean
     */
    public function getIsGroupedSlave()
    {
        return $this->isGroupedSlave == "true" ? true : false;
    }
    /**
     *
     * @param string $isGroupedSlave
     */
    public function setIsGroupedSlave($isGroupedSlave)
    {
        $this->isGroupedSlave = $isGroupedSlave;
    }

    /**
     *
     * @return string
     */
    public function getMasterNodeRef()
    {
        return $this->masterNodeRef;
    }

    /**
     *
     * @param string $masterNodeRef
     */
    public function setMasterNodeRef($masterNodeRef)
    {
        $this->masterNodeRef = $masterNodeRef;
    }

    /**
     * @return string
     */
    public function getCanonicalCorrespondent()
    {
        return $this->getMember();
    }

    /**
     *
     * @return string
     */
    public function getAnsweringMinister()
    {
        return $this->answeringMinister;
    }

    /**
     *
     * @param string $answeringMinister
     */
    public function setAnsweringMinister($answeringMinister)
    {
        $this->answeringMinister = $answeringMinister;
    }

    /**
     *
     * @return string
     */
    public function getAnsweringMinisterId()
    {
        return $this->answeringMinisterId;
    }

    /**
     *
     * @param string $answeringMinisterId
     */
    public function setAnsweringMinisterId($answeringMinisterId)
    {
        $this->answeringMinisterId = $answeringMinisterId;
    }

    /**
     * Method for generating the text to go into the 'More details' column of search
     * @return string
     */
    public function generateMoreDetailsColumnForSearch()
    {
        $moreDetailsString = '';
        if ($this->opDate != null) {
            //@codingStandardsIgnoreStart
            $moreDetailsString .= '<div><label>OP date: </label><span>'.$this->opDate->format('d/m/Y').'</span></div>';
            //@codingStandardsIgnoreEnd
        }
        if ($this->getSignedByHomeSec()) {
            $moreDetailsString .= '<div><label>Signed by: </label><span>Home Sec.</span></div>';
        }
        if ($this->getSignedByLordsMinister()) {
            $moreDetailsString .= '<div><label>Signed by: </label><span>Lords Min</span></div>';
        }
        if (strlen($this->getLordsMinister())) {
            $moreDetailsString .= '<div><label>Signed by: </label><span>' . $this->getLordsMinister() . '</span></div>';
        }
        if ($this->getReviewedByPermSec()) {
            $moreDetailsString .= '<div><label>Reviewed by: </label><span>Perm Sec.</span></div>';
        }
        if ($this->getReviewedBySpads()) {
            $moreDetailsString .= '<div><label>Reviewed by: </label><span>SpAds</span></div>';
        }
        return $moreDetailsString;
    }

    /**
     * @return string
     */
    public function getLordsMinister()
    {
        return $this->lordsMinister;
    }

    /**
     * @param $lordsMinister
     * @return CtsPqCase
     */
    public function setLordsMinister($lordsMinister)
    {
        $this->lordsMinister = $lordsMinister;

        return $this;
    }
}
