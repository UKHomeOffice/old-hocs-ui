<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TransferDepartmentName
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TransferDepartmentName
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function transferDepartmentName(FormBuilderInterface $builder)
    {
        $builder->add('transferDepartmentName', 'text', [
            'label' => 'Department Name'
        ]);

        return $this;
    }
}
