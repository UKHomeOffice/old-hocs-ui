<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\Country;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CorrespondentCountry
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CorrespondentCountry
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function correspondentCountry(FormBuilderInterface $builder)
    {
        $builder->add('correspondentCountry', 'choice', [
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
