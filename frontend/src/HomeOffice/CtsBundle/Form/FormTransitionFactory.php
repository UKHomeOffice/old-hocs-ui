<?php

namespace HomeOffice\CtsBundle\Form;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\CtsBundle\Form\Transition\AbstractTransition;
use HomeOffice\CtsBundle\Form\Transition\TransitionInterface;
use Symfony\Component\Form\Form;

/**
 * Class FormTransitionFactory
 *
 * @package HomeOffice\CtsBundle\Form
 */
class FormTransitionFactory
{
    /**
     * @var AbstractTransition[]
     */
    private $transitions = [];

    /**
     * @var AjaxResponseBuilder
     */
    private $ajaxResponseBuilder;

    /**
     * Constructor
     *
     * @param AjaxResponseBuilder $ajaxResponseBuilder
     */
    public function __construct(AjaxResponseBuilder $ajaxResponseBuilder)
    {
        $this->ajaxResponseBuilder = $ajaxResponseBuilder;
    }

    /**
     * @param Form                $form
     * @param CtsCase             $case
     *
     * @return TransitionInterface
     * @throws \Exception
     */
    public function build(Form $form, CtsCase $case)
    {
        if (!$form->getClickedButton()) {
            throw new \Exception('A form transition has not been actioned.');
        }

        if (array_key_exists($form->getClickedButton()->getName(), $this->transitions) === false) {
            throw new \Exception(sprintf('The %s transition does not exist.', $form->getClickedButton()->getName()));
        }

        $transition = $this->transitions[$form->getClickedButton()->getName()];

        return $transition->prepare($this->ajaxResponseBuilder, $form, $case);
    }

    /**
     * @param TransitionInterface $transition
     * @param string              $name
     */
    public function addTransition(TransitionInterface $transition, $name)
    {
        $this->transitions[$name] = $transition;
    }
}
