<?php

namespace HomeOffice\CtsBundle\Form\Transition;

/**
 * Class AppealCaseTransition
 *
 * @package HomeOffice\CtsBundle\Form\Transition
 */
class AppealCaseTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        switch ($this->getForm()->getClickedButton()->getName()) {
            case 'addAppeal':
                $this->add();
                break;
            case 'removeAppeal':
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

    }

    /**
     * Handle Remove Linked Case
     */
    private function remove()
    {

    }
}
