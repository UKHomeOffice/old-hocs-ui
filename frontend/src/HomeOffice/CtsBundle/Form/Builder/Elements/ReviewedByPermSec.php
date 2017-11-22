<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReviewedByPermSec
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReviewedByPermSec
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function reviewedByPermSec(FormBuilderInterface $builder)
    {
        $builder->add('reviewedByPermSec', 'checkbox', [
            'label' => 'Permanent secretary',
            'disabled' => !in_array($builder->getData()->getCaseStatus(), ['New', 'Draft']),
        ]);

        return $this;
    }
}
