<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TopicUnitList
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TopicUnitList
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $topicUnitListArray
     *
     * @return $this
     */
    public function topicUnitList(FormBuilderInterface $builder, array $topicUnitListArray)
    {
        $builder->add('topicUnitList', 'choice', [
            'choices'     => $topicUnitListArray,
            'empty_value' => '',
            'required'    => false,
            'label'       => 'Topic unit',
            'label_attr'  => ['class' => 'form-label'],
            'attr'        => [
                'class'            =>  'chosen',
                'data-placeholder' => 'Select a topic list'
            ]
        ]);

        return $this;
    }
}
