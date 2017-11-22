<?php

namespace HomeOffice\CtsBundle\Form\Transition;

use HomeOffice\AlfrescoApiBundle\Entity\Person;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;

/**
 * Class ApproveTransition
 *
 * @package HomeOffice\CtsBundle\Form\Transition
 */
class ApproveTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        $clickedButton = $this->getForm()->getClickedButton()->getName();

        if ($clickedButton === 'ApproveToDraft') {
            $this->sendApprovalToTheDrafter();
            $clickedButton = 'SendForDispatch'; // Now update the workflow as if we were SendForDispatch
        }

        $this->saveCase();

        if ($this->getForm()->has('colleagueName') && $this->getForm()->get('colleagueName')->getData() != '') {
            $this->saveMinuteFromField(
                'colleagueName',
                'The case was approved by ' . $this->getForm()->get('colleagueName')->getData()
            );
        }

        $this->updateWorkflow($clickedButton);

        $this
            ->getAjaxResponseBuilder()
            ->setSuccess(true)
            ->setRedirectToRoute('homeoffice_cts_home_home')
        ;
    }

    /**
     * Send the approval to the origin drafter
     */
    private function sendApprovalToTheDrafter()
    {
        $person = $this->getPersonRepository()->getUserDetails($this->getCase()->getOriginalDrafterUser());

        if ($person instanceof Person) {
            $this->getCaseRepository()->assignCaseToPerson($this->getCase(), $person);
        }
    }
}
