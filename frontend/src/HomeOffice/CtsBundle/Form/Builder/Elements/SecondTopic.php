<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondTopic
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondTopic
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $choices
     *
     * @return static
     */
    protected function secondTopic(FormBuilderInterface $builder, array $choices = [])
    {
        $builder->add('secondaryTopic', 'choice', [
            'choices'     => $choices,
            'empty_value' => '',
            'required'    => false,
            'label'       => 'Second Topic',
            'attr'        => [
                'class'            => 'chosen',
                'data-placeholder' => 'Select second topic',
            ],
        ]);

        return $this;
    }
}
