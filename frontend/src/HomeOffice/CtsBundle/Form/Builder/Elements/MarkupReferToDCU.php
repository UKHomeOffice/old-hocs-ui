<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkupReferToDCU
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait MarkupReferToDCU
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function markupReferToDCU(FormBuilderInterface $builder)
    {
        $builder->add('ReferToDCU', 'MarkupReferToDCU', [
            'label'  => '',
            'data'   => $builder->getData(),
            'mapped' => false,
        ]);

        return $this;
    }
}
