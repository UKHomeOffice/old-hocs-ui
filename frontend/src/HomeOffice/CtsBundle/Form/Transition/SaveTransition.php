<?php

namespace HomeOffice\CtsBundle\Form\Transition;

/**
 * Class SaveTransition
 *
 * @package HomeOffice\CtsBundle\Form\FormTransition\Transition
 */
class SaveTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        $this->saveCase();

        switch ($this->getButtonName()) {
            case 'editAnswer':
                $this->getAjaxResponseBuilder()
                    ->setSuccess(true)
                    ->setMessage('The response has been saved')
                    ->setRedirectToRoute('homeoffice_cts_ctscase_jump', ['nodeRef' => $this->getCase()->getNodeId()]);
                break;
            case 'save':
                $this->getAjaxResponseBuilder()
                    ->setSuccess(true)
                    ->setMessage('The case has been saved')
                    ->setCallback('refreshCaseOverview');
                break;
        }
    }
}
