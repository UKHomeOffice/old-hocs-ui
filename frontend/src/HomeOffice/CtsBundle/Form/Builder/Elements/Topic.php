<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Topic
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait Topic
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $choices
     *
     * @return static
     */
    protected function topic(FormBuilderInterface $builder, array $choices = [])
    {
        $builder->add('markupTopic', 'choice', [
            'choices'     => $choices,
            'empty_value' => '',
            'required'    => false,
            'label'       => 'Topic',
            'attr'        => [
                'class'            => 'chosen',
                'data-placeholder' => 'Select topic',
            ],
            'label_attr'  => ['class' => 'form-label']
        ]);

        return $this;
    }
}
