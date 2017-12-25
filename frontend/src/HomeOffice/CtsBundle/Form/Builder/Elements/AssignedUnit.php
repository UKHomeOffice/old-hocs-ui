<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AssignedUnit
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait AssignedUnit
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $choices
     *
     * @return static
     */
    protected function assignedUnit(FormBuilderInterface $builder, array $choices)
    {
        $builder->add('assignedUnit', 'choice', [
            'choices'     => $choices,
            'empty_value' => '',
            'label'       => 'Unit',
            'label_attr'  => ['class' => 'form-label'],
            'data'        => null,
            'attr'        => [
                'class'            => 'chosen form-control form-allocate-unit',
                'data-placeholder' => 'Please select a unit',
            ],
        ]);

        return $this;
    }
}