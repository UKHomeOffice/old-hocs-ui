<?php

namespace HomeOffice\CtsBundle\Form\Transition;

/**
 * Class CloseTransition
 *
 * @package HomeOffice\CtsBundle\Form\Transition
 *
 * @deprecated in
 */
class CloseTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        $form = $this->getForm();

        if ($form->has('ReferToDCU') && $form->get('ReferToDCU')->has('referComment')) {
            // Save a the comment on the ReferToDCU form as a minute
            $this->saveMinuteFromField('ReferToDCU', $form->get('ReferToDCU')->get('referComment')->getData());
        }

        $this->saveCase();

        if ($this->getCase()->getShortName() === 'CtsPqCase') {
            $this->updateWorkflow();
        }

        $this->getAjaxResponseBuilder()
            ->setSuccess(true)
            ->setRedirectToRoute('homeoffice_cts_home_home');
    }
}
