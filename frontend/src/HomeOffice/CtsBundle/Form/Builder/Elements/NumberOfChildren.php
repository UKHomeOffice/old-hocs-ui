<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

trait NumberOfChildren
{
    public function numberOfChildren(FormBuilderInterface $builder)
    {
        $builder->add('numberOfChildren', 'choice', [
            'choices'     => array_combine(range(5, 50), range(5, 50)),
            'empty_value' => '',
            'required'    => false,
            'label'       => 'Number of children',
            'attr'        => [
                'class'            => 'chosen',
                'data-placeholder' => 'Select number of children'
            ],
            'label_attr'  => ['class' => 'form-label']
        ]);

        return $this;
    }
}
