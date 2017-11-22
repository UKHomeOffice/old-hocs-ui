<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Service\MarkupDecisions;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsDcuMinisterialCaseMarkUpType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\MarkUpType
 */
class CtsCaseMarkUpType extends GuftFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $case = $builder->getData();

        $decisions = MarkupDecisions::getGuftDecisionList($case);

        $builder->add('markupDecision', 'choice', [
            'choices' => $case->getCorrespondenceType() === 'FOI'
                ? array_merge(['' => 'Please select a decision'], $decisions)
                : $decisions,
            'label'   => 'Decision',
            'attr'    => ['class' => 'chosen markup-decision'],
            'disabled' => !in_array($case->getCaseStatus(),  ['New', 'Draft'])
        ]);

        foreach ($decisions as $decision) {
            $this->buildMarkupDecision($builder, $decision);
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param string               $decision
     */
    protected function buildMarkupDecision(FormBuilderInterface $builder, $decision)
    {
        switch ($decision) {
            case MarkupDecisions::ALLOCATE_TO_DRAFT:
            case MarkupDecisions::ALLOCATE_TO_POLICY_UNIT:
            case MarkupDecisions::FAQ:
            case MarkupDecisions::POLICY_RESPONSE:
            case MarkupDecisions::ALREADY_IN_PUBLIC_DOMAIN_S21:
            case MarkupDecisions::FEE_THRESHOLD_INVOKED:
            case MarkupDecisions::INFORMATION_NOT_HELD:
            case MarkupDecisions::INFORMATION_RELEASED_IN_FULL:
            case MarkupDecisions::INFORMATION_RELEASED_IN_PART:
            case MarkupDecisions::INFORMATION_WITHHELD_IN_FULL:
            case MarkupDecisions::NEITHER_CONFIRM_ALL:
            case MarkupDecisions::NEITHER_CONFIRM_SOME:
            case MarkupDecisions::REPEAT_REQUEST:
            case MarkupDecisions::REQUEST_UNCLEAR:
            case MarkupDecisions::REQUEST_VEXATIOUS:
                $decision = 'Allocate';
                break;
            default:
                $decision = preg_replace("/[^A-Za-z]/", '', ucwords($decision));
        }

        $builder->add($decision, 'MarkUp' . $decision, [
            'label'          => '',
            'mapped'         => false,
            'data'           => $builder->getData(),
            'error_bubbling' => false,
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsCaseMarkUp';
    }
}
