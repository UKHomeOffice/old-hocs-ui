<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondaryCorrespondentAddressLineThree
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondaryCorrespondentAddressLineThree
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function secondaryCorrespondentAddressLineThree(FormBuilderInterface $builder)
    {
        $builder->add('secondaryCorrespondentAddressLine3', 'text', [
            'attr'     => ['placeholder' => 'Address line 3'],
            'label'    => 'Address',
            'required' => false,
        ]);

        return $this;
    }
}
