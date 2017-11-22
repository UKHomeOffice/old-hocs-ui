<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\CaseProgressHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class GuftFormType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
abstract class GuftFormType extends AbstractType
{
    /**
     * @var string
     */
    protected $workspace;

    /**
     * @var string
     */
    protected $store;

    /**
     * Constructor
     *
     * @param string $workspace
     * @param string $store
     */
    public function __construct($workspace, $store)
    {
        $this->workspace = $workspace;
        $this->store = $store;
    }
    /**
     * @param FormInterface $form
     *
     * @return SubmitButton|null
     */
    protected function getClickedButton(FormInterface $form)
    {
        $clickedButton = null;

        $transitions = $this->getTransitions($form);
        if ($transitions instanceof Form) {
            $clickedButton = $transitions->getClickedButton();
        }

        return $clickedButton;
    }

    /**
     * @param FormInterface $form
     *
     * @return Form|null
     */
    protected function getTransitions(FormInterface $form)
    {
        return $form->getRoot()->get('transitions');
    }

    /**
     * @param CtsCase $case
     *
     * @return bool
     */
    protected function isEditable(CtsCase $case)
    {
        return in_array($case->getCaseStatus(), [
            CaseProgressHelper::PROGRESS_CREATE,
            CaseProgressHelper::PROGRESS_DRAFT
        ]);
    }

    /**
     * @param CtsCase $case
     *
     * @return bool
     */
    protected function isLocked(CtsCase $case)
    {
        return $case->getCaseStatus() === CaseProgressHelper::PROGRESS_COMPLETED;
    }

    /**
     * @param FormBuilderInterface $builder
     */
    protected function applyReadOnly(FormBuilderInterface $builder)
    {
        /** @var FormBuilderInterface $field */
        foreach ($builder->all() as $key => $field) {
            if ($field->getType()->getOptionsResolver()->isKnown('read_only')) {
                $options = array_merge($field->getOptions(), ['read_only' => !$this->isEditable($builder->getData())]);

                $builder->remove($key);
                $builder->add($key, $field->getType()->getName(), $options);
            }
        }
    }
}
