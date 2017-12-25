<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CaseType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CaseType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $types
     *
     * @return static
     */
    public function caseType(FormBuilderInterface $builder, array $types, $inline = true)
    {

        $builder->add('caseType', 'choice', [
            'choices'     => array_merge(['' => 'All'], $types),
            'empty_value' => '',
            'label'       => 'Select case type',
            'label_attr'  => ['class' => 'form-label'],
            'data'        => null,
            'attr'        => [
                'class'            => 'chosen form-control',
                'data-placeholder' => 'Please select a Case Types',
            ],
        ]);

        return $this;
    }
}
