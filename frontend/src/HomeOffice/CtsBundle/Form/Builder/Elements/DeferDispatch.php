<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Priority
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DeferDispatch
{
    /**
     * @param  FormBuilderInterface $builder
     * @return static
     */
    public function deferDispatch(FormBuilderInterface $builder)
    {
        $builder->add(
            'deferDispatch', 'checkbox', [
                'label' => 'Defer dispatch',
                'attr'  => ['class' => 'deferDispatchTrigger']
            ]
        );

        return $this;
    }
}
