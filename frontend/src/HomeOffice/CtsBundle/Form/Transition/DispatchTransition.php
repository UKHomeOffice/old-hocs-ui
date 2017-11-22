<?php

namespace HomeOffice\CtsBundle\Form\Transition;

/**
 * Class DispatchTransition
 *
 * @package HomeOffice\CtsBundle\Form\Transition
 */
class DispatchTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        $this->saveCase();
        $this->updateWorkflow($this->getButtonName() == 'Dispatch' ? 'Next' : $this->getButtonName());

        $this->getAjaxResponseBuilder()
            ->setSuccess(true)
            ->setRedirectToRoute('homeoffice_cts_home_home');
    }
}
