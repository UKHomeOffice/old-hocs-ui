<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsCaseAllocate
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\Components
 */
trait CtsCaseAllocate
{
    /**
     * @param FormBuilderInterface $builder
     */
    public function buildAllocateForm(FormBuilderInterface $builder)
    {
        $builder->add('allocate', 'CtsCaseAllocate', [
            'data'   => $builder->getData(),
            'mapped' => false,
        ]);
    }
}
