<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CaseStatus
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CaseStatus
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $choices
     *
     * @return static
     */
    public function caseStatus(FormBuilderInterface $builder, array $choices)
    {
        $builder->add('caseStatus', 'choice', [
            'choices'     => $choices,
            'empty_value' => '',
            'required'    => false,
            'label'       => 'State',
            'label_attr'  => ['class' => 'form-label'],
            'attr'        => [
                'class'            => 'chosen form-control',
                'data-placeholder' => 'Select Stage',
            ],
        ]);

        return $this;
    }
}
