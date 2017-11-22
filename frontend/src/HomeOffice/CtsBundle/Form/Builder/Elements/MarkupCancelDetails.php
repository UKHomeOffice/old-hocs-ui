<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkupCancelDetails
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait MarkupCancelDetails
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function markupCancelDetails(FormBuilderInterface $builder)
    {
        $builder->add('markupCancelDetails', 'textarea', [
            'label'  => 'Further details (optional)',
            'mapped' => false,
            'attr'   => [
                'class' => 'form-control form-control-full',
                'rows'  => '6',
            ],
        ]);

        return $this;
    }
}





