<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkupTopic
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait MarkupTopic
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $choices
     * @param bool                 $standardLines
     *
     * @return static
     */
    protected function markupTopic(FormBuilderInterface $builder, array $choices = [], $standardLines = false)
    {
        $builder->add('markupTopic', 'choice', [
            'choices'     => $choices,
            'empty_value' => '',
            'required'    => false,
            'label'       => 'Topic',
            'attr'        => [
                'class'               => 'chosen markup-topic',
                'data-placeholder'    => 'Select topic',
                'data-standard-lines' => $standardLines === true
            ],
            'label_attr'  => ['class' => 'form-label']
        ]);

        return $this;
    }
}
