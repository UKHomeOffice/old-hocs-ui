<?php

namespace HomeOffice\CtsBundle\Form\Transition;

/**
 * Class ManualAllocateTransition
 *
 * @package HomeOffice\CtsBundle\Form\FormTransition\Transition
 */
class ManualAllocateTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        $this->getAjaxResponseBuilder()
            ->setSuccess(true)
            ->setCallback('applyManualTransition', [
                $this->getCase()->getAssignedUnit(),
                $this->getCase()->getAssignedTeam(),
                $this->getCase()->getAssignedUser(),
            ]);
    }
}
