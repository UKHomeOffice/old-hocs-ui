<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReviewedBySpads
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReviewedBySpads
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function reviewedBySpads(FormBuilderInterface $builder)
    {
        $builder->add('reviewedBySpads', 'checkbox', [
            'label'    => 'Special advisor',
            'disabled' => !in_array($builder->getData()->getCaseStatus(), ['New', 'Draft']),
        ]);

        return $this;
    }
}
