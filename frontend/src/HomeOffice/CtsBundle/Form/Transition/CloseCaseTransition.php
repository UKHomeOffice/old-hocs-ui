<?php

namespace HomeOffice\CtsBundle\Form\Transition;

/**
 * Class CloseCaseTransition
 *
 * @package HomeOffice\CtsBundle\Form\Transition
 */
class CloseCaseTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        $this->saveCase();
        $this->updateWorkflow();

        $this->getAjaxResponseBuilder()
            ->setSuccess(true)
            ->setRedirectToRoute('homeoffice_cts_home_home');
    }
}
