<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondaryCorrespondentAddressLineOne
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondaryCorrespondentAddressLineOne
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function secondaryCorrespondentAddressLineOne(FormBuilderInterface $builder)
    {
        $builder->add('secondaryCorrespondentAddressLine1', 'text', [
            'attr'     => ['placeholder' => 'Address line 1'],
            'label'    => 'Address',
            'required' => false,
        ]);

        return $this;
    }
}
