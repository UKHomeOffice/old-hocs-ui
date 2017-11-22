<?php

namespace HomeOffice\CtsBundle\Form\Transition;

use HomeOffice\AlfrescoApiBundle\Entity\Person;

/**
 * Class NextTransition
 *
 * @package HomeOffice\CtsBundle\Form\FormTransition\Transition
 */
class NextTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        $clickedButton = $this->getButtonName();

        if ($this->getCase()->getShortName() == 'CtsDcuMinisterialCase' &&
            ($this->getCase()->getCaseTask() == 'Draft response' ||
            $this->getCase()->getCaseTask() == 'Amend response') &&
            $clickedButton == 'Next'
        ) {
            $clickedButton = 'SendForQAReview';
        }

        $this->saveMinuteFromField('minute');
        $this->saveCase();
        $this->updateWorkflow($clickedButton);

        $this->getAjaxResponseBuilder()
            ->setSuccess(true)
            ->setRedirectToRoute('homeoffice_cts_home_home');
    }
}
