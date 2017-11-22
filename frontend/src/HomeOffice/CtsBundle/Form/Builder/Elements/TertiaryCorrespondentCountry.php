<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\Country;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TertiaryCorrespondentCountry
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TertiaryCorrespondentCountry
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function tertiaryCorrespondentCountry(FormBuilderInterface $builder)
    {
        $builder->add('thirdPartyCorrespondentCountry', 'choice', [
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
