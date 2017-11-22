<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class OfficeOfOrigin
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait OfficeOfOrigin
{
    /**
     * @param FormBuilderInterface $builder
     * @param array|null $choices
     *
     * @return static
     */
    protected function officeOfOrigin(FormBuilderInterface $builder, array $choices = null)
    {
        if (is_null($choices)) {
            $builder->add('officeOfOrigin', 'text', [
                'label'      => 'Office of origin',
                'label_attr' => ['class' => 'form-label'],
                'attr'       => ['class' => 'form-control']
            ]);
        } else {
            $builder->add('officeOfOrigin', 'choice', [
                'choices'    => $choices,
                'label'      => 'Office of origin',
                'required'   => false,
                'attr'       => [
                    'class'            => 'chosen',
                    'data-placeholder' => 'Select office of origin'
                ],
                'label_attr' => ['class' => 'form-label'],
            ]);
        }

        return $this;
    }
}