<?php

namespace HomeOffice\CtsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CtsViewCaseType extends AbstractType
{
    /**
     * @var SecurityContext
     */
    private $securityContext;
 
    /**
     * @var string
     */
    private $actionType;

    /**
     *
     * @param SecurityContext $securityContext
     * @param string $actionType
     */
    public function __construct($securityContext, $actionType)
    {
        $this->securityContext = $securityContext;
        $this->actionType = $actionType;
    }
 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $ctsCase = $builder->getData();
        $builder
            ->add('id', 'hidden')
            ->add('caseStatus', 'hidden')
            ->add('caseTask', 'hidden')
            ->add('correspondenceType', 'hidden')
            ->add('assignedUser', 'hidden')
            ->add('assignedUnit', 'hidden')
            ->add('assignedTeam', 'hidden')
            ->add('canDeleteObject', 'hidden');
     
        $nextTransitions = $ctsCase->getNextStateTransitions();
        $rejectTransition = $ctsCase->getRejectStateTransition();
     
        if ($this->securityContext->isGranted('assigned', $ctsCase) &&
            $nextTransitions != null
        ) {
            $i = 1;
            foreach ($nextTransitions as $transition) {
                $builder
                    ->add('NextStateButton'.$i, 'submit')
                    ->add(
                        'nextStateTransition'.$i,
                        'hidden',
                        array('mapped' => false, 'data' => $transition->getValue())
                    );
                $i++;
            }
        }
     
        if ($this->securityContext->isGranted('assigned', $ctsCase) &&
            $rejectTransition
        ) {
            $builder
                ->add('RejectStateButton', 'submit')
                ->add(
                    'rejectStateTransition',
                    'hidden',
                    array('mapped' => false, 'data' => $rejectTransition->getValue())
                );
        }
     
        if ($this->securityContext->isGranted('delete', $ctsCase)) {
            $builder
                ->add('DeleteButton', 'submit', array(
                    'label' => 'Delete'
                ));
        }
    }
 
    public function getName()
    {
        return 'view_case_type';
    }
}
