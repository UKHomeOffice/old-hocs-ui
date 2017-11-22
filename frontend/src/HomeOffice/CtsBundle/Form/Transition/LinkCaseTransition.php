<?php

namespace HomeOffice\CtsBundle\Form\Transition;

use Symfony\Component\Form\FormError;

/**
 * Class LinkCaseTransition
 *
 * @package HomeOffice\CtsBundle\Form\Transition
 */
class LinkCaseTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        switch ($this->getButtonName()) {
            case 'addLinkedCase':
                $this->add();
                break;

            case 'removeLinkedCase':
                $this->remove();
                break;
        }
    }

    /**
     * Handle Add Linked Case
     *
     * @throws \Twig_Error
     */
    private function add()
    {
        $result = $this->getCaseRepository()->addLinkedCases($this->getCase());
        if ($result !== true) {
            $this->getForm()->get('linkedCases')->get('hrnsToLink')->addError(new FormError($result));
            return;
        }

        $template = $this->getAjaxResponseBuilder()
            ->getTemplate('HomeOfficeCtsBundle:CaseElements:linkedCasesTable.html.twig', [
                'case' => $this->getCaseRepository()->getCase($this->getCase()->getNodeId()),
                'form' => $this->getForm()->get('linkedCases')->createView(),
            ]);

        $this->getAjaxResponseBuilder()
            ->setSuccess(true)
            ->setMessage('The case is linked to ' . $this->getCase()->getHrnsToLink())
            ->setCallback('updateNode', ['.linkedCaseTable', $template]);
    }

    /**
     * Handle Remove Linked Case
     */
    private function remove()
    {
        $linkedNodeId = $this->getForm()->get('linkedCases')->get('linkedCaseToRemove')->getData();

        foreach ($this->getCase()->getLinkedCases() as $linkedCase) {
            if ($linkedCase->getNodeId() == $linkedNodeId) {
                $this->getCaseRepository()->removeLinkedCase($this->getCase(), $linkedCase);

                $this->getAjaxResponseBuilder()
                    ->setSuccess(true)
                    ->setMessage('The case is no longer linked to ' . $linkedCase->getUrn())
                    ->setCallback('removeCase', ['linked', $linkedCase->getNodeId()]);
                return;
            }
        }
    }
}
