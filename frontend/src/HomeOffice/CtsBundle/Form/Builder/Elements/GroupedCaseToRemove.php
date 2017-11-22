<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class GroupedCaseToRemove
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait GroupedCaseToRemove
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function groupedCaseToRemove(FormBuilderInterface $builder)
    {
        $builder->add('groupedCaseToRemove', 'hidden', [
            'mapped' => false,
            'attr'   => ['class' => 'groupedCaseToRemove'],
        ]);

        return $this;
    }
}

