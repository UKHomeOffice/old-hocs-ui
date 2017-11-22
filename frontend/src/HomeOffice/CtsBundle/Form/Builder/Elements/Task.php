<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

trait Task
{
    public function task(FormBuilderInterface $builder, array $choices)
    {
        $builder->add('task', 'choice', [
            'choices'     => $choices,
            'empty_value' => '',
            'required'    => false,
            'label'       => 'Task',
            'attr'        => [
                'class'            => 'chosen',
                'data-placeholder' => 'Select task',
            ],
            'label_attr'  => ['class' => 'form-label']
        ]);

        return $this;
    }
}
