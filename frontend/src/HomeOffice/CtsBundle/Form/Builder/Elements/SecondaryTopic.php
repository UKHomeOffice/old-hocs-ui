<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\ListBundle\Service\ListService;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondaryTopic
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondaryTopic
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $choices
     * @param bool                $standardLines
     *
     * @return static
     */
    protected function secondaryTopic(FormBuilderInterface $builder, array $choices = [], $standardLines = false)
    {
        $builder->add('secondaryTopic', 'choice', [
            'choices'     => $choices,
            'empty_value' => '',
            'required'    => false,
            'attr'        => [
                'class'               => 'chosen markup-topic markup-topic-secondary',
                'data-placeholder'    => 'Select secondary topic',
                'data-standard-lines' => $standardLines === true
            ],
        ]);

        return $this;
    }
}
