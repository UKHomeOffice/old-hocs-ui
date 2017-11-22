<?php

namespace HomeOffice\CtsBundle\Form\Transition;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoCorCase;

/**
 * Class DeferTransition
 *
 * @package HomeOffice\CtsBundle\Form\Transition
 */
class DeferTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        if ($this->getButtonName() == 'CancelDefer') {
            /** @var CtsHmpoCorCase $case */
            $case = $this->getCase();

            $case->setBringUpDate(null);
            $case->setDeferDueTo(null);
        }

        $this->saveCase();
        $this->updateWorkflow();

        $this->getAjaxResponseBuilder()
            ->setSuccess(true)
            ->setRedirectToRoute('homeoffice_cts_home_home');
    }
}
