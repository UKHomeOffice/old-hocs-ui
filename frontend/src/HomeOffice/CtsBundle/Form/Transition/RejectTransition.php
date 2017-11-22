<?php

namespace HomeOffice\CtsBundle\Form\Transition;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;

/**
 * Class RejectTransition
 *
 * @package HomeOffice\CtsBundle\Form\Transition
 */
class RejectTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        $this->updateWorkflow('Reject');
        $this->saveMinuteFromField('returnReason');

        $this->getAjaxResponseBuilder()
            ->setSuccess(true)
            ->setRedirectToRoute('homeoffice_cts_home_home');
    }
}
