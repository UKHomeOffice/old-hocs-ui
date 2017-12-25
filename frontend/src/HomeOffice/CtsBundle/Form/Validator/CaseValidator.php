<?php

namespace HomeOffice\CtsBundle\Form\Validator;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCaseWorkflowTransition;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCaseWorkflowValidation;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\SubmitButton;

/**
 * Class CaseValidator
 *
 * @package HomeOffice\CtsBundle\Form\Validation
 */
class CaseValidator
{

    /**
     * @var ExpressionLanguage
     */
    protected $language;

    /**
     * @var CtsCase
     */
    protected $case;

    /**
     * Constructor
     *
     * @param ExpressionLanguage $language
     */
    public function __construct(ExpressionLanguage $language)
    {
        $this->language = $language;
    }

    /**
     * @param FormInterface $form
     */
    public function validate(FormInterface $form)
    {
        $this->case = $form->getData();

        /** @var SubmitButton $clickedButton */
        $clickedButton = method_exists($form, 'getClickedButton') ? $form->getClickedButton() : null;

        if ($clickedButton instanceof SubmitButton) {
            switch ($clickedButton->getName()) {
                case 'save':
                case 'addDocument':
                case 'removeDocument':
                case 'addLinkedCase':
                case 'removeLinkedCase':
                case 'addGroupedCase':
                case 'removeGroupedCase':
                case 'Return':
                case 'Reject':
                    break;

                case 'ManualAllocate':
                case 'Reallocate':
                    $this->validateAllocation($form, $clickedButton);
                    break;

                default:
                    $this->validateMandatoryFields($form);
                    $this->validateAllocation($form, $clickedButton);
                    break;
            }
        }
    }

    /**
     * Validate the mandatory fields
     *
     * @param FormInterface $form
     */
    protected function validateMandatoryFields(FormInterface $form)
    {
        /** @var CtsCase $case */
        $case = $form->getData();

        foreach ($form->all() as $field) {
            if (!$field instanceof Form) {
                // Only validate Form objects
                continue;
            }

            if ($field->count()) {
                // Recursively apply rules to form collections
                $this->validateMandatoryFields($field);
            }

            if (!$form->getData() instanceof CtsCase) {
                // Only validate when the form entity is a CtsCase
                continue;
            }

            if ($case->getCaseWorkflowStatus() && $case->getCaseWorkflowStatus()->hasMandatoryField($field, $form)) {
                $mandatoryField = $case->getCaseWorkflowStatus()->getMandatoryField($field, $form);

                if (in_array($field->getName(), ['markupTeam', 'markupTopic'])) {
                    // @todo this is here as alfresco fails to save markupTeam and there are no markupTopics available
                    continue;
                }

                $result = $this->language->evaluate($mandatoryField->getExpression(), [
                    'case' => $this->case,
                    'form' => $this->prepareFormForEvaluation($form),
                ]);

                if ($result === false) {
                    $field->addError(new FormError($mandatoryField->getMessage()));
                }
            }
        }
    }

    /**
     * @param FormInterface  $form
     *
     * @return \stdClass
     */
    protected function prepareFormForEvaluation(FormInterface $form)
    {
        $fields = new \stdClass();

        foreach ($form->all() as $field) {
            if ($field->getData() instanceof CtsCase) {
                $fields->{$field->getName()} = $this->prepareFormForEvaluation($field);
            } else {
                $fields->{$field->getName()} = $field->getData();
            }
        }

        return $fields;
    }

    /**
     * Validate the allocation
     *
     * @param FormInterface $form
     * @param SubmitButton  $clickedButton
     */
    protected function validateAllocation(FormInterface $form, SubmitButton $clickedButton)
    {
        $form = $form->has('allocate') ? $form->get('allocate') : $form;

        if ($form->has('allocateTo') &&
            $clickedButton->getConfig()->hasAttribute('transition') &&
            $clickedButton->getConfig()->getAttribute('transition')->isManualAllocate()
        ) {
            if ($form->get('allocateTo')->getData() === null) {
                $form->get('allocateTo')->addError(
                    new FormError('Allocate to yourself or select a colleague')
                );
            } else {
                $this->validateVirtualFields($form, [
                    new CtsCaseWorkflowValidation('assignedUnit', 'Select a unit to allocate this case to'),
                ]);
            }
        }
    }

    /**
     * @param FormInterface               $form
     * @param CtsCaseWorkflowValidation[] $mandatoryFields
     */
    protected function validateVirtualFields(FormInterface $form, array $mandatoryFields)
    {
        foreach ($mandatoryFields as $mandatoryField) {
            if ($form->has($mandatoryField->getName())) {
                if ($this->language->evaluate($mandatoryField->getExpression(), ['case' => $this->case]) === false) {
                    $form->get($mandatoryField->getName())->addError(new FormError($mandatoryField->getMessage()));
                }
            }
        }
    }
}