<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FeeIncluded
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait FeeIncluded
{
    /**
     * @param FormBuilderInterface $builder
     * @return static
     */
    protected function feeIncluded(FormBuilderInterface $builder)
    {
        $builder->add('feeIncluded', 'choice', [
            'choices'    => [ true  => 'Yes', false => 'No' ],
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => [ 'class' => 'inline deliveryTypeTrigger' ],
            'label'      => 'Fee included?',
            'label_attr' => [ 'class' => 'block-label' ]
        ]);

        return $this;
    }
}
