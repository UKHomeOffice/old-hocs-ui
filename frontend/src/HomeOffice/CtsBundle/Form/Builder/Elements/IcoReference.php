<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class IcoReference
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait IcoReference
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function icoReference(FormBuilderInterface $builder)
    {
        $builder->add('icoReference', 'text', [
            'label' => 'ICO reference'
        ]);

        return $this;
    }
}
