<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CorrespondentAddressLine3
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CorrespondentAddressLineThree
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function correspondentAddressLineThree(FormBuilderInterface $builder)
    {
        $builder->add('correspondentAddressLine3', 'text', [
            'attr'     => [
                'class' => 'form-control form-control-3-4 form-control-spaced',
            ],
            'label'    => 'Address',
            'required' => false,
        ]);

        return $this;
    }
}
