<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Member
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait Member
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $choices
     * @param bool                 $flexible
     *
     * @return static
     */
    protected function member(FormBuilderInterface $builder, array $choices = [], $flexible = false)
    {
        $builder->add('member', 'choice', [
            'choices'     => $choices,
            'empty_value' => '',
            'required'    => true,
            'attr'        => [
                'class'            => $flexible ? 'flexible-chosen' : 'chosen',
                'data-placeholder' => 'Select member',
            ],
        ]);

        return $this;
    }
}