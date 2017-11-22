<?php

namespace HomeOffice\CtsBundle\Form\Transition;

/**
 * Class CancelCaseTransition
 *
 * @package HomeOffice\CtsBundle\Form\Transition
 */
class CancelCaseTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        $this->saveCase();
        $this->updateWorkflow();

        $this->saveMinuteFromField('cancelReason');
        $this->saveMinuteFromField('responsePhoneComment');

        $this->getAjaxResponseBuilder()
            ->setSuccess(true)
            ->setRedirectToRoute('homeoffice_cts_home_home');
    }
}
