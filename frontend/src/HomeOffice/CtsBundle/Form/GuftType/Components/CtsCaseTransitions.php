<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsCaseTransitions
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\Components
 */
trait CtsCaseTransitions
{
    /**
     * @param FormBuilderInterface $builder
     */
    public function buildTransitionsForm(FormBuilderInterface $builder)
    {
        $builder->add('transitions', 'CtsCaseTransitions', [
            'mapped'         => false,
            'data'           => $builder->getData(),
            'error_bubbling' => false,
        ]);
    }
}
