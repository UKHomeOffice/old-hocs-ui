<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkupMinister
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait MarkupMinister
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $choices
     *
     * @return static
     */
    public function markupMinister(FormBuilderInterface $builder, array $choices = [])
    {
        $builder->add('markupMinister', 'choice', [
            'choices'     => $choices,
            'required'    => false,
            'empty_value' => '',
            'label'       => 'Sign off Minister',
            'attr'        => [
                'class'     => 'chosen',
                'data-placeholder' => 'Select minister'
            ]
        ]);

        return $this;
    }
}
