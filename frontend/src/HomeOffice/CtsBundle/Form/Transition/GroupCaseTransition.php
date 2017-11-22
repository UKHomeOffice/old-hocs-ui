<?php

namespace HomeOffice\CtsBundle\Form\Transition;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase;
use Symfony\Component\Form\FormError;

/**
 * Class GroupCaseTransition
 *
 * @package HomeOffice\CtsBundle\Form\Transition
 */
class GroupCaseTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        switch ($this->getForm()->getClickedButton()->getName()) {
            case 'addGroupedCase':
                $this->add();

                break;
            case 'removeGroupedCase':
                $this->remove();
                break;
        }
    }

    /**
     * @return CtsPqCase
     */
    protected function getCase()
    {
        return parent::getCase();
    }

    /**
     * Handle Add Grouped Case
     *
     * @throws \Twig_Error
     */
    private function add()
    {
        $result = $this->getCaseRepository()->addGroupedCases($this->getCase());
        if ($result !== true) {
            $this->getForm()->get('groupedCases')->get('uinsToGroup')->addError(new FormError($result));
            return;
        }

        $template = $this->getAjaxResponseBuilder()
            ->getTemplate('HomeOfficeCtsBundle:CaseElements:groupedCasesTable.html.twig', [
                'case' => $this->getCaseRepository()->getCase($this->getCase()->getNodeId()),
                'form' => $this->getForm()->get('groupedCases')->createView(),
            ]);

        $this->getAjaxResponseBuilder()
            ->setSuccess(true)
            ->setMessage('The case is grouped with ' . $this->getCase()->getUinsToGroup())
            ->setCallback('updateNode', ['.groupedCaseTable', $template]);
    }

    /**
     * Handle Remove Grouped Case
     */
    private function remove()
    {
        $groupedNodeId = $this->getForm()->get('groupedCases')->get('groupedCaseToRemove')->getData();

        foreach ($this->getCase()->getGroupedCases() as $groupedCase) {
            if ($groupedCase->getNodeId() == $groupedNodeId) {
                $this->getCaseRepository()->removeGroupedCases($this->getCase(), [$groupedCase]);

                $this->getAjaxResponseBuilder()
                    ->setSuccess(true)
                    ->setMessage('The case is no longer grouped with ' . $groupedCase->getUin())
                    ->setCallback('removeCase', ['grouped', $groupedCase->getNodeId()]);
                return;
            }
        }
    }
}
