<?php

namespace HomeOffice\CtsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceSubType;
use HomeOffice\AlfrescoApiBundle\Service\MarkupDecisions;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

abstract class CtsCaseType extends AbstractType
{

    /**
     * @var array
     */
    protected static $validCaseTypesForOGD = array(
        'IMCB',
        'IMCM',
        'COM',
        'GEN',
        'MIN',
        'TRO'
    );

    /*
     * @var string
     */
    protected $formPurpose;

    /*
     * @var string
     */
    protected $cascadeValidation;

    /**
     *
     * @var string
     */
    protected $workspace;

    /**
     *
     * @var string
     */
    protected $store;

    /**
     *
     * @var ListHandler
     */
    protected $ctsListHandler;

    /**
     *
     * @var CtsHelper
     */
    protected $ctsHelper;

    /**
     *
     * @var array
     */
    protected $memberList;

    /** @var
     * array
     */
    protected $requiredFields;

    /**
     * @var array
     */
    protected $validateIf = array();

    /**
     * @var array
     */
    protected $validateIfStatus = array();

    /**
     * @var array
     */
    protected $validateIfTask = array();

    /**
     *
     * @param string $formPurpose
     * @param ListHandler $ctsListHandler
     * @param CtsHelper $ctsHelper
     * @param boolean $cascadeValidation
     */
    public function __construct($formPurpose, $ctsListHandler, $ctsHelper, $cascadeValidation = false)
    {
        $this->formPurpose = $formPurpose;
        $this->cascadeValidation = $cascadeValidation;
        $this->ctsListHandler = $ctsListHandler;
        $this->ctsHelper = $ctsHelper;
    }

    /**
     *
     * @param string $workspace
     */
    public function setWorkspace($workspace)
    {
        $this->workspace = $workspace;
    }

    /**
     *
     * @param string $store
     */
    public function setStore($store)
    {
        $this->store = $store;
    }

    /**
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CtsCase $ctsCase */
        $ctsCase = $builder->getData();
        $this->validateIfStatus = $ctsCase->getCaseMandatoryFieldStatus();
        $this->validateIfTask   = $ctsCase->getCaseMandatoryFieldTask();
        $this->validateIf       = $ctsCase->getCaseMandatoryFieldDependencies();

        // ok to use super type as any classes with extend it will also be instanceof it.
        $urn = ($ctsCase instanceof CtsCase) ? $ctsCase->getUrn() : '';

        $this->memberList = $this->populateMembersNameList();
        $topicList = [];
        $topicList = $this->ctsHelper->handleTopicList($topicList);
        $secondaryTopicList = $topicList;
        $unitList = $this->ctsListHandler->getList('ctsUnitList');
        $ministerList = $this->ctsListHandler->getList('ctsMinisterList');
        $decisionList = MarkupDecisions::getMarkupDecisionsArrayForWorkflowStage($ctsCase);
        $correspondenceSubTypes = CaseCorrespondenceSubType::getCorrespondenceSubTypeArray();
        $displayCorrespondenceType = '';
        $correspondenceType = '';

        if ($ctsCase != null) {
            $linkedCases = $ctsCase->getLinkedCases();
            $topicList = $this->ctsHelper->handleLegacyValue($topicList, $ctsCase->getMarkupTopic());
            $secondaryTopicList = $this->ctsHelper->handleLegacyValue(
                $secondaryTopicList,
                $ctsCase->getSecondaryTopic()
            );
            $ministerList = $this->ctsHelper->handleLegacyValue($ministerList, $ctsCase->getMarkupMinister());
            $decisionList = $this->ctsHelper->handleLegacyValue($decisionList, $ctsCase->getMarkupDecision());
            $unitList = $this->ctsHelper->handleLegacyValue($unitList, $ctsCase->getMarkupUnit());
            $displayCorrespondenceType = $correspondenceSubTypes[$ctsCase->getCorrespondenceType()];
            $correspondenceType = $ctsCase->getCorrespondenceType();
        }
        $builder
        ->add('caseResponseDeadline', 'date', array(
            'empty_value' => '-',
            'disabled' => true
        ))
        ->add('caseStatus', 'text', array(
            'attr' => array('class' => 'plain'),
            'label' => 'Status',
            'disabled' => true,
        ))
        ->add('caseTask', 'text', array(
            'attr' => array('class' => 'plain'),
            'label' => 'Task',
            'disabled' => true,
        ))
        ->add('urn', 'text', array(
            'data' => $urn,
            'disabled' => true,
            'label' => 'HRN',
            'mapped' => 'false'
        ))
        ->add('correspondenceType', 'text', array(
            'data' => $displayCorrespondenceType,
            'label' => 'Case type',
            'disabled' => true
        ))
        ->add('markupTopic', 'choice', array(
            'choices' => $topicList,
            'empty_value' => 'Select topic',
            'required'  => false,
            'label' => 'Topic',
            'attr' => array(
                'class' => 'markup-topic'
            )
        ))
        ->add('markupMinister', 'choice', array(
            'choices' => $ministerList,
            'required'  => false,
            'empty_value' => 'Select Minister',
            'label' => 'Minister',
        ))
        ->add('secondaryTopic', 'choice', array(
            'choices' => $secondaryTopicList,
            'empty_value' => 'Select secondary topic',
            'required'  => false,
            'attr' => array(
                'class' => 'markup-secondary-topic'
            )
        ))
        ->add('newDocument', new CtsCaseDocumentType($this->formPurpose, $this->workspace, $this->store))
        ->add('Save1', 'submit', array(
            'label' => 'Save',
        ))
        ->add('Save2', 'submit', array(
            'label' => 'Save'
        ));

        if ($correspondenceType == 'IMCM') {
            $builder
            ->add('markupMinister', 'choice', array(
                'choices' => $ministerList,
                'required'  => false,
                'empty_value' => 'Select Minister',
                'label' => 'Minister *',
            ));
        } else {
            $builder
            ->add('markupMinister', 'choice', array(
                'choices' => $ministerList,
                'required'  => false,
                'empty_value' => 'Select Minister',
                'label' => 'Minister',
            ));
        }

        if ($correspondenceType == 'IMCM' || $correspondenceType == 'IMCB') {
            $builder
            ->add('markupDecision', 'choice', array(
                'choices' => $decisionList,
                'empty_value' => 'Select decision',
                'required'  => false,
                'label' => 'Decision *',
                'attr' => array(
                    'class' => 'markup-decision'
                )
            ))
            ->add('markupUnit', 'choice', array(
                'choices' => $unitList,
                'empty_value' => 'Select unit',
                'required'  => false,
                'label' => 'Unit *',
                'attr' => array(
                   'class' => 'markup-unit'
                )
            ));
        } else {
            $builder
            ->add('markupDecision', 'choice', array(
                'choices' => $decisionList,
                'empty_value' => 'Select decision',
                'required'  => false,
                'label' => 'Decision',
                'attr' => array(
                    'class' => 'markup-decision'
                )
            ))
            ->add('markupUnit', 'choice', array(
                'choices' => $unitList,
                'empty_value' => 'Select unit',
                'required'  => false,
                'label' => 'Unit',
                'attr' => array(
                    'class' => 'markup-unit'
                )
                ));
        }

        if ($this->formPurpose == 'edit') {
            $builder->add(
                'newMinute',
                new CtsCaseMinuteType(
                    $ctsCase->getId(),
                    ($ctsCase->isQaEligible()) ? $ctsCase->getCaseTask() : null
                )
            );
        }

        if (true === in_array($correspondenceType, self::$validCaseTypesForOGD)) {
            $builder->add(
                'ogdName',
                'text',
                array(
                    'required'  => false,
                    'label' => 'Department Name',
                )
            );
        }

        $builder
        ->add('hrnsToLink', 'text', array(
            'label' => "HRNs",
            'attr' => array(
                'class' => "linked-hrns-class"
            )
        ))
        ->add('addLinkedCases', 'submit', array(
            'label' => "Link"
        ));
        if (isset($linkedCases)) {
            foreach ($linkedCases as $linkedCase) {
                $builder
                ->add('linkedCase_'.$linkedCase->getNodeId(), 'text', array(
                    'label' => '',
                    'data' => $linkedCase->getUrn(),
                    'disabled' => true,
                    'mapped' => false
                ));
            }
        }
    }

    /**
     * @return array
     */
    protected function populateMembersNameList()
    {
        $membersNameListArray = array();

        foreach ($this->ctsListHandler->getList('ctsMemberList') as $member) {
            $membersNameListArray[$member->getDisplayName()] = $member->getDisplayName();
        }
        return $membersNameListArray;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'ctsCase';
    }

    /**
     * @param array $requiredFields
     * @return CtsFoiCaseType
     */
    public function setRequiredFields(array $requiredFields)
    {
        $this->requiredFields = $requiredFields;

        return $this;
    }

    /**
     * @return array
     */
    public function getRequiredFields()
    {
        return $this->requiredFields;
    }

    /**
     * @param FormBuilderInterface $builder
     */
    protected function bindRequiredFormFields(FormBuilderInterface $builder)
    {
        if (count($this->getRequiredFields()) > 0) {
            $builder->setRequired(true);
            foreach ($this->getRequiredFields() as $name => $value) {
                if (true === $builder->has($name)) {
                    $builder->get($name)->setRequired(true);
                }
            }
        }
    }

    /**
     * @param FormInterface $form
     * @param string        $caseStatus
     * @param string        $caseTask
     * @return boolean
     */
    public function hasRequiredFormFields(FormInterface &$form, $caseStatus, $caseTask = '')
    {
        $isValid = true;

        if (true === $this->isRequiredStatus($caseStatus) || true === $this->isRequiredTask($caseTask)) {
            foreach ($this->getRequiredFields() as $name => $message) {
                if ($form->has($name) && $this->isRequiredForValidation($form, $name)) {
                    $formData = $form->get($name)->getData();

                    $formData = (true === is_string($formData))
                        ? trim($formData)
                        : $formData;

                    if (true === empty($formData)) {
                        $form->get($name)->addError(new FormError($message));
                        $isValid &= false;
                    }
                }
            }
        }

        return $isValid;
    }

    /**
     * @param FormInterface $form
     * @param               $fieldName
     * @return bool
     */
    public function isRequiredForValidation(FormInterface $form, $fieldName)
    {
        if (false === array_key_exists($fieldName, $this->validateIf)) {
            return true;
        }

        $fieldData = ($form->has($this->validateIf[$fieldName]['field']))
            ? $form->get($this->validateIf[$fieldName]['field'])->getData()
            : null;

        $validationValues = $this->validateIf[$fieldName]['values'];

        //We are not comparing validation based on a field value, so we just check if it is required
        if (true === empty($validationValues)) {
            return (
                array_key_exists($fieldName, $this->validateIf) &&
                $fieldData &&
                false === empty($fieldData)
            );
        }

        return (
            in_array($fieldData, $validationValues) &&
            $fieldData &&
            false === empty($fieldData)
        );


    }

    /**
     * @param  string $caseStatus
     * @return bool
     */
    public function isRequiredStatus($caseStatus)
    {
        return in_array($caseStatus, $this->validateIfStatus);
    }

    /**
     * @param string $caseTask
     * @return bool
     */
    public function isRequiredTask($caseTask)
    {
        return in_array($caseTask, $this->validateIfTask);
    }
}
