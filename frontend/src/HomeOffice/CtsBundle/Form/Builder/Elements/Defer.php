<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Save
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait Defer
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function defer(FormBuilderInterface $builder)
    {
        $builder->add('Defer', 'submit', [
            'attr' => ['class' => 'button'],
        ]);

        return $this;
    }
}
