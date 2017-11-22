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
            'choices'    => array_merge(['' => 'All'], $types),
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline'],
            'label'      => 'Select case type',
            'label_attr' => ['class' => $inline ? 'block-label' : 'block-label-clear']
        ]);

        return $this;
    }
}
