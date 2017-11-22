<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\Country;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondaryCorrespondentCountry
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondaryCorrespondentCountry
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function secondaryCorrespondentCountry(FormBuilderInterface $builder)
    {
        $builder->add('secondaryCorrespondentCountry', 'choice', [
            'choices'     => Country::getCountriesArray(),
            'label'       => 'Country',
            'empty_value' => '',
            'attr'        => [
                'class'            => 'chosen',
                'data-placeholder' => 'Select country',
            ],
        ]);

        return $this;
    }
}
