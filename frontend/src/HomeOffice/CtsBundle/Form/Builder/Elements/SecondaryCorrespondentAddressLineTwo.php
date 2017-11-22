<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondaryCorrespondentAddressLineTwo
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondaryCorrespondentAddressLineTwo
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function secondaryCorrespondentAddressLineTwo(FormBuilderInterface $builder)
    {
        $builder->add('secondaryCorrespondentAddressLine2', 'text', [
            'attr'     => ['placeholder' => 'Address line 2'],
            'label'    => 'Address',
            'required' => false,
        ]);

        return $this;
    }
}
