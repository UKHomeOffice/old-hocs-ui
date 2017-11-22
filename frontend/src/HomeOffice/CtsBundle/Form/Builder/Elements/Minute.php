<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Minute
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait Minute
{
    /**
     * @param FormBuilderInterface $builder
     * @param string|null          $label
     *
     * @return static
     */
    protected function minute(FormBuilderInterface $builder, $label = null)
    {
        $builder->add('minute', 'textarea', [
            'label' => $label ?: 'Minute',
            'attr'  => [
                'class' => 'form-control form-control-full',
                'rows'  => '6',
                'data-readOnlyPlaceholder' => $label . ' are saved in the minutes',
            ],
            'mapped' => false,
        ]);

        return $this;
    }
}
