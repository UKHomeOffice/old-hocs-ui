<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkupReferToOGD
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait MarkupReferToOGD
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function markupReferToOGD(FormBuilderInterface $builder)
    {
        $builder->add('ReferToOGD', 'MarkupReferToOGD', [
            'label'  => '',
            'data'   => $builder->getData(),
            'mapped' => false,
        ]);

        return $this;
    }
}
