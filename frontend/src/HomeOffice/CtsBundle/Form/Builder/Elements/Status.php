<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

trait Status
{
     public function status(FormBuilderInterface $builder, array $choices)
     {
         $builder->add('status', 'choice', [
             'choices'     => $choices,
             'empty_value' => '',
             'required'    => false,
             'label'       => 'Status',
             'label_attr'  => ['class' => 'form-label'],
             'attr'        => [
                 'class'            =>  'chosen markup-unit',
                 'data-placeholder' => 'Select status'
             ],
             'disabled'    => method_exists($this, 'isDisabled') ? $this->isDisabled($builder->getData()) : false,
         ]);

         return $this;
     }
}
