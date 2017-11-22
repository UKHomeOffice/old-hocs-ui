<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AssociatedTopic
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait AssociatedTopic
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $choices
     *
     * @return static
     */
    protected function associatedTopic(FormBuilderInterface $builder, array $choices = [])
    {
        $builder->add('associatedTopic', 'choice', [
            'choices'     => $choices,
            'empty_value' => '',
            'required'    => false,
            'label'       => 'Topic',
            'attr'        => [
                'class'            => 'chosen',
                'data-placeholder' => 'Select topic',
                'disabled'         => empty($choices),
            ],
            'label_attr'  => ['class' => 'form-label']
        ]);

        return $this;
    }
}
