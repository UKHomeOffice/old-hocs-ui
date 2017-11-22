<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AssignedTeam
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait AssignedTeam
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function assignedTeam(FormBuilderInterface $builder)
    {
        $builder->add('assignedTeam', 'choice', [
            'choices'     => [],
            'empty_value' => '',
            'label'       => 'Team',
            'label_attr'  => ['class' => 'form-label'],
            'data'        => null,
            'attr'        => [
                'class'            => 'chosen form-control form-allocate-team',
                'data-placeholder' => 'Please select a team',
                'disabled'         => 'disabled',
            ],
        ]);

        return $this;
    }
}
