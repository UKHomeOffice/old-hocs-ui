<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\Cases;

use Symfony\Component\Form\FormInterface;

/**
 * Class CtsCaseWorkflowStatus
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity\Cases
 */
class CtsCaseWorkflowStatus
{
    /**
     * @var CtsCaseWorkflowTransition[]
     */
    private $transitions;
 
    /**
     *
     * @var CtsCaseWorkflowValidation[]
     */
    private $mandatoryFields;
 
    /**
     *
     * @param CtsCaseWorkflowTransition[] $transitions
     * @param CtsCaseWorkflowValidation[] $mandatoryFields
     */
    public function __construct($transitions, $mandatoryFields)
    {
        $this->transitions = $transitions;
        $this->mandatoryFields = $mandatoryFields;
    }
 
    /**
     * @return CtsCaseWorkflowTransition[]
     */
    public function getTransitions()
    {
        return $this->transitions;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public function hasTransition($value)
    {
        foreach ($this->transitions as $transition) {
            if ($transition->getValue() == $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return CtsCaseWorkflowValidation[]
     */
    public function getMandatoryFields()
    {
        return $this->mandatoryFields;
    }

    /**
     * @param FormInterface      $field
     * @param FormInterface|null $parent
     *
     * @return bool
     */
    public function hasMandatoryField(FormInterface $field, FormInterface $parent = null)
    {
        return array_key_exists($this->getMandatoryFieldName($field, $parent), $this->mandatoryFields);
    }

    /**
     * @param FormInterface      $field
     * @param FormInterface|null $parent
     *
     * @return CtsCaseWorkflowValidation
     */
    public function getMandatoryField(FormInterface $field, FormInterface $parent = null)
    {
        if ($this->hasMandatoryField($field, $parent)) {
            return $this->mandatoryFields[$this->getMandatoryFieldName($field, $parent)];
        }
    }

    /**
     * @param CtsCaseWorkflowTransition[] $transitions
     */
    public function setTransitions($transitions)
    {
        $this->transitions = $transitions;
    }

    /**
     * @param CtsCaseWorkflowValidation[] $mandatoryFields
     */
    public function setMandatoryFields($mandatoryFields)
    {
        $this->mandatoryFields = $mandatoryFields;
    }

    /**
     * @param FormInterface      $field
     * @param FormInterface|null $parent
     *
     * @return string
     */
    protected function getMandatoryFieldName(FormInterface $field, FormInterface $parent = null)
    {
        $name = $field->getName();
        if (!is_null($parent) && $parent->isRoot() === false) {
            $name = $parent->getName() . ':' . $name;
        }

        return $name;
    }
}
