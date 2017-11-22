<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CorrespondentAddressLine2
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CorrespondentAddressLineTwo
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function correspondentAddressLineTwo(FormBuilderInterface $builder)
    {
        $builder->add('correspondentAddressLine2', 'text', [
            'attr'     => [
                'class' => 'form-control form-control-3-4 form-control-spaced',
            ],
            'label'    => 'Address',
            'required' => false,
        ]);

        return $this;
    }
}
