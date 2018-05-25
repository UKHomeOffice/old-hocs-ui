<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;
use HomeOffice\AlfrescoApiBundle\Entity\CtsNode;
use HomeOffice\AlfrescoApiBundle\Entity\Person;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseDocumentFactory;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseMinuteFactory;
use HomeOffice\AlfrescoApiBundle\Service\CtsCaseMinutesHelper;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use HomeOffice\CtsBundle\Utils\CtsFeaturesToggle;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CtsCase
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity\Cases
 */
class CtsCase extends CtsNode
{
    /**
     * @var string
     */
    protected $objectTypeId = 'F:cts:case';

    /**
     * @var string
     */
    protected $folderName;

    /**
     * @var \DateTime
     */
    protected $dateCreated;

    /**
     * @var string
     */
    protected $caseStatus;

    /**
     * @var string
     */
    protected $caseTask;

    /**
     * @var Person
     */
    protected $caseOwner;

    /**
     * @var string
     */
    protected $urnSuffix;

    /**
     * @var \DateTime
     */
    protected $caseResponseDeadline;

    /**
     * @var string $correspondenceType
     *
     * @Assert\NotBlank()
     */
    protected $correspondenceType;

    /**
     * @var string
     */
    protected $markupDecision;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Markup-Allocate", "Case_Create_UKVI", "Case_Create_NO10"}, message="Select an answering unit")
     *
     */
    protected $markupUnit;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Markup-Allocate", "Case_Create_UKVI", "Case_Create_NO10"}, message="Select an answering team")
     *
     */
    protected $markupTeam;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Markup-Allocate", "Case_Create_UKVI", "Case_Create_NO10"}, message="Select a markup topic")
     */
    protected $markupTopic;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Markup-Allocate", "Case_ParlyApproval"}, message="Select a sign off Minister")
     */
    protected $markupMinister;

    /**
     * @var string
     */
    protected $markupMinisterName;

    /**
     * @var string
     */
    protected $secondaryTopic;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Select a unit to allocate this case to")
     */
    protected $assignedUnit;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Select a team to allocate this case to")
     */
    protected $assignedTeam;

    /**
     * @var string
     */
    protected $assignedUser;

    /**
     * @var array
     */
    protected $caseMinutes = [];

    /**
     * @var CtsCaseMinute
     */
    protected $newMinute;

    /**
     * @var array
     */
    protected $caseDocuments = [];

    /**
     * @var CtsCaseDocument
     */
    protected $newDocument;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Case_Linked"}, message="Select another case to link to")
     */
    protected $hrnsToLink;

    /**
     * @var array
     */
    protected $linkedCases = [];

    /**
     *
     * @var boolean
     */
    protected $isLinkedCase;

    /**
     * @var boolean
     */
    protected $canUpdateProperties;

    /**
     * @var boolean
     */
    protected $canDeleteObject;

    /**
     * @var boolean
     */
    protected $canAssignUser;

    /**
     *
     * @var CtsCaseWorkflowStatus
     */
    protected $caseWorkflowStatus;

    /**
     * @var array
     */
    protected $caseMandatoryFields = array();

    /**
     * @var array
     */
    protected $caseMandatoryFieldDependencies = array();

    /**
     * @var array
     */
    protected $caseMandatoryFieldStatus = array();

    /**
     * @var array
     */
    protected $caseMandatoryFieldTask = array();

    /**
     *
     * @var string
     */
    protected $originalDrafterUnit;

    /**
     *
     * @var string
     */
    protected $originalDrafterTeam;

    /**
     *
     * @var string
     */
    protected $originalDrafterUser;

    /**
     *
     * @var string
     *
     * @Assert\NotBlank(groups={"Markup-ReferToOGD"}, message="Select department name")
     *
     */
    protected $ogdName;

    /**
     *
     * @var \DateTime
     */
    protected $statusUpdatedDatetime;

    /**
     *
     * @var \DateTime
     */
    protected $taskUpdatedDatetime;

    /**
     *
     * @var \DateTime
     */
    protected $ownerUpdatedDatetime;

    /**
     *
     * @var CTSHelper
     */
    private $ctsHelper;

    /**
     * @var array
     */
    protected $permissions;

    /**
     *
     * @param string $workspace
     * @param string $store
     */
    public function __construct($workspace, $store)
    {
        parent::__construct($workspace, $store);
        $this->ctsHelper = new CTSHelper(null, null);
    }

    /**
     *
     * @return string
     */
    public function getObjectTypeId()
    {
        return $this->objectTypeId;
    }

    /**
     *
     * @param string $objectTypeId
     */
    public function setObjectTypeId($objectTypeId)
    {
        $this->objectTypeId = $objectTypeId;
    }

    /**
     *
     * @return string
     */
    public function getFolderName()
    {
        return $this->folderName;
    }

    /**
     *
     * @param string $folderName
     */
    public function setFolderName($folderName)
    {
        $this->folderName = $folderName;
    }

    /**
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     *
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = DateHelper::forceDateTimeOrBlank($dateCreated);
    }

    /**
     *
     * @return CtsCaseDocument
     */
    public function getNewDocument()
    {
        return $this->newDocument;
    }

    /**
     *
     * @param CtsCaseDocument $newDocument
     */
    public function setNewDocument(CtsCaseDocument $newDocument)
    {
        $this->newDocument = $newDocument;
    }

    /**
     * @return string
     */
    public function getCorrespondenceType()
    {
        return $this->correspondenceType;
    }

    /**
     * @param string $correspondenceType
     */
    public function setCorrespondenceType($correspondenceType)
    {
        $this->correspondenceType = $correspondenceType;
    }

    /**
     * @return Person
     */
    public function getCaseOwner()
    {
        return $this->caseOwner;
    }

    /**
     * @param string  $caseOwner
     * @return object CtsCase
     */
    public function setCaseOwner($caseOwner)
    {
        $this->caseOwner = $caseOwner;
        return $this;
    }

    /**
     * @return string
     */
    public function getCaseTask()
    {
        return $this->caseTask;
    }

    /**
     * @param string $caseTask
     * @return object CtsCase
     */
    public function setCaseTask($caseTask)
    {
        $this->caseTask = $caseTask;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrnSuffix()
    {
        return $this->urnSuffix;
    }

    /**
     * @param string $urnSuffix
     * @return object CtsCase
     */
    public function setUrnSuffix($urnSuffix)
    {
        $this->urnSuffix = $urnSuffix;
        return $this;
    }

     /**
     * @return string
     */
    public function getUrn()
    {
        if ($this->urnSuffix != '' && $this->correspondenceType != '') {
            return $this->correspondenceType . "/" . $this->urnSuffix;
        } else {
            return '';
        }
    }

    /**
     * @return \DateTime
     */
    public function getCaseResponseDeadline()
    {
        return $this->caseResponseDeadline;
    }

    /**
     * @param string $caseResponseDeadline
     * @return object CtsCase
     */
    public function setCaseResponseDeadline($caseResponseDeadline)
    {
        $this->caseResponseDeadline = DateHelper::forceDateTimeOrBlank($caseResponseDeadline);
        return $this;
    }

    /**
     * @return string
     */
    public function getCaseStatus()
    {
        return $this->caseStatus;
    }

    /**
     * @param string $caseStatus
     *
     * @return CtsCase
     */
    public function setCaseStatus($caseStatus)
    {
        $this->caseStatus = $caseStatus;

        return $this;
    }

    /**
     * @return string
     */
    public function getMarkupDecision()
    {
        return $this->markupDecision;
    }

    /**
     * @param string $markupDecision
     */
    public function setMarkupDecision($markupDecision)
    {
        $this->markupDecision = $markupDecision;
    }

    /**
     * @return string
     */
    public function getMarkupUnit()
    {
        return $this->markupUnit;
    }

    /**
     * @param string $markupUnit
     */
    public function setMarkupUnit($markupUnit)
    {
        $this->markupUnit = $markupUnit;
    }

    /**
     * @return string
     */
    public function getMarkupTeam()
    {
        return $this->markupTeam;
    }

    /**
     * @param string $markupTeam
     */
    public function setMarkupTeam($markupTeam)
    {
        $this->markupTeam = $markupTeam;
    }

    /**
     * @return string
     */
    public function getMarkupTopic()
    {
        return $this->markupTopic;
    }

    /**
     * @param string $markupTopic
     */
    public function setMarkupTopic($markupTopic)
    {
        $this->markupTopic = $markupTopic;
    }

    /**
     * @return string
     */
    public function getMarkupMinister()
    {
        return $this->markupMinister;
    }

    /**
     * @param string $markupMinister
     */
    public function setMarkupMinister($markupMinister)
    {
        $this->markupMinister = $markupMinister;
    }

    /**
     * @return string
     */
    public function getMarkupMinisterName()
    {
        return $this->markupMinisterName;
    }

    /**
     * @param string $markupMinisterName
     */
    public function setMarkupMinisterName($markupMinisterName)
    {
        $this->markupMinisterName = $markupMinisterName;
    }

    /**
     * @return string
     */
    public function getSecondaryTopic()
    {
        return $this->secondaryTopic;
    }

    /**
     * @param string $secondaryTopic
     */
    public function setSecondaryTopic($secondaryTopic)
    {
        $this->secondaryTopic = $secondaryTopic;
    }

        /**
     *
     * @return string
     */
    public function getAssignedUnit()
    {
        return $this->assignedUnit;
    }

    /**
     *
     * @param string $assignedUnit
     */
    public function setAssignedUnit($assignedUnit)
    {
        $this->assignedUnit = $assignedUnit;
    }

    /**
     *
     * @return string
     */
    public function getAssignedTeam()
    {
        return $this->assignedTeam;
    }

    /**
     *
     * @param string $assignedTeam
     */
    public function setAssignedTeam($assignedTeam)
    {
        $this->assignedTeam = $assignedTeam;
    }

    /**
     *
     * @return string
     */
    public function getAssignedUser()
    {
        return $this->assignedUser;
    }

    /**
     *
     * @param string $assignedUser
     */
    public function setAssignedUser($assignedUser)
    {
        $this->assignedUser = $assignedUser;
    }

    /**
     *
     * @return CtsCaseMinute
     */
    public function getNewMinute()
    {
        return $this->newMinute;
    }

    /**
     *
     * @param CtsCaseMinute $newMinute
     */
    public function setNewMinute($newMinute)
    {
        $this->newMinute = $newMinute;
    }


    /**
     * @return Array
     */
    public function getCaseMinutes()
    {
        return $this->caseMinutes;
    }

    /**
     *
     * @return boolean
     */
    public function getCanAssignUser()
    {
        return $this->canAssignUser == "true" ? true : false;
    }

    /**
     *
     * @param string $canAssignUser
     */
    public function setCanAssignUser($canAssignUser)
    {
        $this->canAssignUser = $canAssignUser;
    }

    /**
     *
     * @return array
     */
    public function getPropertyPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param string
     * @return boolean
     */
    public function canEditPropertyPermission($property)
    {
        if (isset($this->permissions->$property)) {
            if ($this->permissions->$property === 'false') {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     * @param array $permissions
     * @return object CtsCase
     */
    public function setPropertyPermissions($permissions)
    {
        $this->permissions = $permissions;
        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getCanUpdateProperties()
    {
        return $this->canUpdateProperties == "true" ? true : false;
    }

    /**
     *
     * @param string $canUpdateProperties
     */
    public function setCanUpdateProperties($canUpdateProperties)
    {
        $this->canUpdateProperties = $canUpdateProperties;
    }

        /**
     *
     * @return string
     */
    public function getCanDeleteObject()
    {
        return $this->canDeleteObject == "true";
    }

    /**
     *
     * @param string $canDeleteObject
     */
    public function setCanDeleteObject($canDeleteObject)
    {
        $this->canDeleteObject = $canDeleteObject;
    }

    /**
     * @param array $caseMinutes
     * @return object CtsCase
     */
    public function setCaseMinutes(array $caseMinutes)
    {
        $this->caseMinutes = array();
        $minuteFactory = new CtsCaseMinuteFactory();
        foreach ($caseMinutes as $caseMinute) {
            if ($caseMinute instanceof CtsCaseMinute) {
                array_push($this->caseMinutes, $caseMinute);
            } else {
                array_push($this->caseMinutes, $minuteFactory->build($caseMinute));
            }
        }
        return $this;
    }

    /**
     * @return CtsCaseDocument[]
     */
    public function getCaseDocuments()
    {
        return $this->caseDocuments;
    }

    /**
     * @param array $caseDocuments
     * @return CtsCase
     */
    public function setCaseDocuments(array $caseDocuments)
    {
        $this->caseDocuments = array();
        $documentFactory = new CtsCaseDocumentFactory($this->getWorkspace(), $this->getStore());
        foreach ($caseDocuments as $caseDocument) {
            if ($caseDocument instanceof CtsCaseDocument) {
                array_push($this->caseDocuments, $caseDocument);
            } else {
                array_push($this->caseDocuments, $documentFactory->build($caseDocument));
            }
        }
        return $this;
    }

    /**
     * @param string $documentType
     *
     * @return bool
     */
    public function hasCaseDocument($documentType)
    {
        foreach ($this->getCaseDocuments() as $caseDocument) {
            if ($caseDocument->getDocumentType() === $documentType) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @param CtsCaseDocument $ctsCaseDocument
     */
    public function addCaseDocument(CtsCaseDocument $ctsCaseDocument)
    {
        array_push($this->caseDocuments, $ctsCaseDocument);
    }

    /**
     * @return CtsCase[]
     */
    public function getLinkedCases()
    {
        return $this->linkedCases;
    }

    /**
     * @return bool
     */
    public function hasLinkedCases()
    {
        return $this->linkedCases ? true : false;
    }

    /**
     * Must be passed an array of CtsCase objects.
     * @param array $linkedCases
     */
    public function setLinkedCases(array $linkedCases)
    {
        $this->linkedCases = $linkedCases;
    }

    /**
     *
     * @param string $hrnsToLink
     */
    public function setHrnsToLink($hrnsToLink)
    {
        $this->hrnsToLink = $hrnsToLink;
    }

    /**
     *
     * @return string
     */
    public function getHrnsToLink()
    {
        return $this->hrnsToLink;
    }

    /**
     *
     * @return boolean
     */
    public function getIsLinkedCase()
    {
        return $this->isLinkedCase == "true" ? true : false;
    }

    /**
     *
     * @param string $isLinkedCase
     */
    public function setIsLinkedCase($isLinkedCase)
    {
        $this->isLinkedCase = $isLinkedCase;
    }

    /**
     * @return CtsCaseWorkflowStatus
     */
    public function getCaseWorkflowStatus()
    {
        return $this->caseWorkflowStatus;
    }

    /**
     *
     * @param string $caseWorkflowStatus
     * @param bool|null $featureToggle
     */
    public function setCaseWorkflowStatus($caseWorkflowStatus, $featureToggle = null)
    {
        $transitions = json_decode($caseWorkflowStatus);

        $transitionArray = [];
        if (isset($transitions->transitions)) {
            foreach ($transitions->transitions as $transition) {
                $transitionArray[$transition->value] = new CtsCaseWorkflowTransition(
                    $transition->label,
                    $transition->value,
                    $transition->manualAllocate,
                    isset($transition->allocateHeader) ? $transition->allocateHeader : '',
                    $transition->colour
                );
            }
        }

        $mandatoryFieldArray = [];
        if (isset($transitions->mandatoryFields)) {
            foreach ($transitions->mandatoryFields as $mandatoryField) {
                $mandatoryFieldArray[$mandatoryField->name] = new CtsCaseWorkflowValidation(
                    $mandatoryField->name,
                    $mandatoryField->message,
                    isset($mandatoryField->expression) ? $mandatoryField->expression : null
                );
            }
        }
        $this->caseWorkflowStatus = new CtsCaseWorkflowStatus($transitionArray, []);
    }

    /**
     * @return boolean|array
     */
    public function getNextStateTransitions()
    {
        $transitions = array();

        if (null !== $this->caseWorkflowStatus &&
            null !== $this->caseWorkflowStatus->getTransitions()
        ) {
            /** @var CtsCaseWorkflowTransition $transition */
            foreach ($this->caseWorkflowStatus->getTransitions() as $transition) {
                if (false === $transition->getManualAllocate() &&
                    'Reject' !== $transition->getValue()
                ) {
                    array_push($transitions, $transition);
                }
            }
        }

        return (count($transitions) > 0)
            ? $transitions
            : null;

    }

    /**
     *
     * @return boolean|CtsCaseWorkflowTransition
     */
    public function getRejectStateTransition()
    {
        if ($this->caseWorkflowStatus != null && $this->caseWorkflowStatus->getTransitions() != null) {
            /** @var CtsCaseWorkflowTransition $transition */
            foreach ($this->caseWorkflowStatus->getTransitions() as $transition) {
                if (false === $transition->getManualAllocate() &&
                    'Reject' === $transition->getValue()
                ) {
                    return $transition;
                }
            }
        }

        return false;
    }

    /**
     *
     * @return string
     */
    public function nextStatusTransition()
    {
        return 'Next';
    }

    /**
     *
     * @return string
     */
    public function previousStatusTransition()
    {
        return 'Reject';
    }

    /**
     *
     * @return string
     */
    public function getOriginalDrafterUnit()
    {
        return $this->originalDrafterUnit;
    }

    /**
     *
     * @return string
     */
    public function getOriginalDrafterTeam()
    {
        return $this->originalDrafterTeam;
    }

    /**
     *
     * @return string
     */
    public function getOriginalDrafterUser()
    {
        return $this->originalDrafterUser;
    }

    /**
     *
     * @param string $originalDrafterUnit
     */
    public function setOriginalDrafterUnit($originalDrafterUnit)
    {
        $this->originalDrafterUnit = $originalDrafterUnit;
    }

    /**
     *
     * @param string $originalDrafterTeam
     */
    public function setOriginalDrafterTeam($originalDrafterTeam)
    {
        $this->originalDrafterTeam = $originalDrafterTeam;
    }

    /**
     *
     * @param string $originalDrafterUser
     */
    public function setOriginalDrafterUser($originalDrafterUser)
    {
        $this->originalDrafterUser = $originalDrafterUser;
    }

    /**
     *
     * @return string
     */
    public function getOgdName()
    {
        return $this->ogdName;
    }

    /**
     *
     * @param string $ogdName
     */
    public function setOgdName($ogdName)
    {
        $this->ogdName = $ogdName;
    }

    /**
     *
     * @return \DateTime
     */
    public function getStatusUpdatedDatetime()
    {
        return $this->statusUpdatedDatetime;
    }

    /**
     *
     * @return \DateTime
     */
    public function getTaskUpdatedDatetime()
    {
        return $this->taskUpdatedDatetime;
    }

    /**
     *
     * @return \DateTime
     */
    public function getOwnerUpdatedDatetime()
    {
        return $this->ownerUpdatedDatetime;
    }

    /**
     *
     * @param mixed $statusUpdatedDatetime
     * @return CtsCase
     */
    public function setStatusUpdatedDatetime($statusUpdatedDatetime)
    {
        $this->statusUpdatedDatetime = DateHelper::forceDateTimeOrBlank($statusUpdatedDatetime);
        return $this;
    }

    /**
     * @param mixed $taskUpdatedDatetime
     * @return CtsCase
     */
    public function setTaskUpdatedDatetime($taskUpdatedDatetime)
    {
        $this->taskUpdatedDatetime = DateHelper::forceDateTimeOrBlank($taskUpdatedDatetime);
        return $this;
    }

    /**
     * @param mixed $ownerUpdatedDatetime
     * @return CtsCase
     */
    public function setOwnerUpdatedDatetime($ownerUpdatedDatetime)
    {
        $this->ownerUpdatedDatetime = DateHelper::forceDateTimeOrBlank($ownerUpdatedDatetime);
        return $this;
    }

    /**
     * @return string
     */
    public function getCanonicalCorrespondent()
    {
        return '-';
    }

    /**
     * Method for generating the text to go into the 'More details' column of search
     * @return string
     */
    public function generateMoreDetailsColumnForSearch()
    {
        return '';
    }

    /**
     * @deprecated For state-specific mandatory fields recommend using caseWorkflowStatus->mandatoryFields
     * @return array
     */
    public function getCaseMandatoryFields()
    {
        return $this->caseMandatoryFields;
    }

    /**
     * @param $mandatoryFields
     * @return CtsCase
     */
    public function setCaseMandatoryFields($mandatoryFields)
    {
        $this->caseMandatoryFields = array();

        $mandatoryData = json_decode($mandatoryFields, true);

        if (0 !== count($mandatoryData)) {
            foreach ($mandatoryData as $mandatoryField) {
                foreach ($mandatoryField as $mandatoryFieldEntry) {
                    $this->caseMandatoryFields[$mandatoryFieldEntry['name']] = $mandatoryFieldEntry['message'];
                }
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getCaseMandatoryFieldStatus()
    {
        return $this->caseMandatoryFieldStatus;
    }

    /**
     * @param  string $statuses
     * @return CtsCase
     */
    public function setCaseMandatoryFieldStatus($statuses)
    {
        $this->caseMandatoryFieldStatus = array();

        $data = json_decode($statuses, true);

        if (0 !== count($data)) {
            foreach ($data as $field) {
                $this->caseMandatoryFieldStatus[] = $field[0];
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getCaseMandatoryFieldTask()
    {
        return $this->caseMandatoryFieldTask;
    }

    /**
     * @param  string $tasks
     * @return CtsCase
     */
    public function setCaseMandatoryFieldTask($tasks)
    {
        $this->caseMandatoryFieldTask = array();

        $data = json_decode($tasks, true);

        if (0 !== count($data)) {
            foreach ($data as $field) {
                $this->caseMandatoryFieldTask[] = $field[0];
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getCaseMandatoryFieldDependencies()
    {
        return $this->caseMandatoryFieldDependencies;
    }

    /**
     * @param $validationDependencies
     * @return CtsCase
     */
    public function setCaseMandatoryFieldDependencies($validationDependencies)
    {
        $this->caseMandatoryFieldDependencies = array();

        $data = json_decode($validationDependencies, true);

        if (0 !== count($data)) {
            foreach ($data as $field) {
                foreach ($field as $dependency) {
                    list($key, $value) = each($dependency);
                    $this->caseMandatoryFieldDependencies[$key] = $value;
                }
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isQaEligible()
    {
        return (
            in_array(
                strtolower($this->caseTask),
                CtsCaseMinutesHelper::getCaseMinutesStatuses()
            )
        );
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get'.ucwords($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return null;
    }
}
