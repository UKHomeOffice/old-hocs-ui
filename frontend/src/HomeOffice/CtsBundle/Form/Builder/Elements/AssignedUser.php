<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AssignedUser
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait AssignedUser
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function assignedUser(FormBuilderInterface $builder)
    {
        $builder->add('assignedUser', 'choice', [
            'choices'     => [],
            'empty_value' => '',
            'label'       => 'User',
            'label_attr'  => ['class' => 'form-label'],
            'data'        => null,
            'attr'        => [
                'class'            => 'chosen form-control form-allocate-user',
                'data-placeholder' => 'Please select a user',
                'disabled'         => 'disabled',
            ],
        ]);
    }
}
