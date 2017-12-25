<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\CaseProgressHelper;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Transitions
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait Transitions
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function transitions(FormBuilderInterface $builder)
    {
        /** @var CtsCase $case */
        $case = $builder->getData();

        $hasManualAllocation = false;

        if ($case->getCaseWorkflowStatus()) {
            foreach ($case->getCaseWorkflowStatus()->getTransitions() as $transition) {
                if ($transition->getValue() == 'Reallocate') {
                    continue;
                }

                if ($transition->isManualAllocate() && $builder->has('allocateTo') === false) {
                    $hasManualAllocation = true;
                    $options = [
                        'label' => $transition->getLabel(),
                        'attr'  => [
                            'class'                  => 'button button-modal transition-'.$transition->getValue(),
                            'data-href'              => sprintf(
                                '/cts/cases/manual-allocation/%s/%s',
                                $case->getNodeId(),
                                $transition->getValue()
                            ),
                        ],
                    ];
                } else {
                    if ($transition->getValue() == 'Reject')
                        $class = 'button-secondary';
                    else
                        $class = 'button';
                    $options = [
                        'label' => $transition->getLabel(),
                        'attr'  => [
                            'class' => $class,
                        ],
                    ];
                }

                $builder->add($transition->getValue(), 'submit', $options);
                $builder->get($transition->getValue())->setAttribute('transition', $transition);
            }
        }

        if ($hasManualAllocation) {
            $builder->add('ManualAllocation', 'ManualAllocation', [
                'mapped' => false,
                'data'   => $builder->getData(),
            ]);
        }

        $this->polyfillUntilAlfrescoIsUpToDate($builder);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @todo Move this logic to Alfresco
     */
    protected function polyfillUntilAlfrescoIsUpToDate(FormBuilderInterface $builder)
    {
        /** @var CtsCase $case */
        $case = $builder->getData();

        if ($case->getShortName() === 'CtsUkviCase' && $case->getCaseStatus() == CaseProgressHelper::PROGRESS_APPROVE) {
            //@todo Add the ApproveToDraft Button as it is not in the workflow. It should be.
            $builder->add('ApproveToDraft', 'submit', [
                'attr' => ['class' => 'button'],
            ]);
        }

        if (in_array($case->getShortName(), ['CtsUkviCase', 'CtsNo10Case', 'CtsFoiCase', 'CtsFoiComplaintCase']) &&
            $case->getCaseStatus() == CaseProgressHelper::PROGRESS_DISPATCH
        ) {
            //@todo Rename the Next transition to Dispatch for Ukvi cases when dispatching
            $builder->add('Dispatch', 'submit', [
                'attr' => ['class' => 'button'],
                'label' => 'Dispatched',
            ]);
            $builder->remove('Dispatched');
        }

        if ($case->getCorrespondenceType() === 'IMCM' && $case->getCaseStatus() === CaseProgressHelper::PROGRESS_DRAFT) {
            $builder->add('SendToDispatch', 'submit', [
                'attr' => ['class' => 'button'],
                'label' => 'Send to dispatch'
            ]);
        }
    }
}
