<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Trait StandardLineName
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait StandardLineName
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function standardLineName(FormBuilderInterface $builder)
    {
        $builder->add('name', 'text', [
            'required' => false,
        ]);

        return $this;
    }
}