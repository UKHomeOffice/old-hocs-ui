<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\AllCountry;
use Symfony\Component\Form\FormBuilderInterface;

trait CountryOfDestination
{
    public function countryOfDestination(FormBuilderInterface $builder)
    {
        $builder->add('countryOfDestination', 'choice', [
            'choices'     => AllCountry::getCountriesArray(),
            'empty_value' => '',
            'required'    => false,
            'multiple'    => true,
            'label'       => '',
            'attr'        => [
                'class'            => 'chosen',
                'data-placeholder' => 'Country of destination',
            ],
            'label_attr'  => ['class' => 'form-label']
        ]);

        return $this;
    }
}
