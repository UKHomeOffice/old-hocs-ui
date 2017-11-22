<?php

namespace HomeOffice\CtsBundle\Form\Transition;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;
use HomeOffice\AlfrescoApiBundle\Entity\Person;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseMinuteRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsWorkflowRepository;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;

/**
 * Class ReallocateTransition
 *
 * @package HomeOffice\CtsBundle\Form\Transition
 */
class ReallocateTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        $this->updateWorkflow('Reallocate');
        $this->saveMinuteFromField('reallocationReason');

        $this->getAjaxResponseBuilder()
            ->setSuccess(true)
            ->setRedirectToRoute('homeoffice_cts_ctscase_jump', ['nodeRef' => $this->getCase()->getNodeId()]);
    }
}
