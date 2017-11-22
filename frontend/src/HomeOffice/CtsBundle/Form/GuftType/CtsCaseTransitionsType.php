<?php
namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceSubType;
use HomeOffice\AlfrescoApiBundle\Service\CaseProgressHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsCaseTransitionsType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsCaseTransitionsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CtsCase $case */
        $case = $builder->getData();

        if ($case->getCaseWorkflowStatus()) {
            foreach ($case->getCaseWorkflowStatus()->getTransitions() as $transition) {
                $builder->add($transition->getValue(), 'submit', [
                    'attr' => ['class' => 'button'],
                    'label' => $transition->getLabel(),
                ]);
                $builder->get($transition->getValue())->setAttribute('transition', $transition);
            }
        }

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

        if (
            $case->getShortName()          === 'CtsUkviCase' &&
            $case->getCorrespondenceType() === 'IMCM' &&
            $case->getCaseStatus()         === CaseProgressHelper::PROGRESS_DRAFT
        ) {
            $builder->add('SendToDispatch', 'submit', [
                'attr' => ['class' => 'button'],
                'label' => 'Send to dispatch'
            ]);
        }

        // Dirty nested conditionals...
        if ($case->getShortName() === 'CtsNo10Case' && $case->getCorrespondenceType() === 'DTEN') {
            if ($case->getCaseStatus() === CaseProgressHelper::PROGRESS_APPROVE) {
                $builder->add('SendQAReviewForHSPrivateOfficeApproval', 'submit', [
                    'attr'  => ['class' => 'button'],
                    'label' => 'Submit for approval'
                ]);
            }

            if ($case->getCaseStatus() === CaseProgressHelper::PROGRESS_SIGNOFF) {
                $builder->add('SendHSPrivateOfficeApprovalForHSSignOff', 'submit', [
                    'attr'  => ['class' => 'button'],
                    'label' => 'Submit for approval'
                ])->add('Return', 'submit', [
                    'attr'  => ['class' => 'button-secondary'],
                    'label' => 'Return'
                ]);
            }
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsCaseTransitions';
    }
}
