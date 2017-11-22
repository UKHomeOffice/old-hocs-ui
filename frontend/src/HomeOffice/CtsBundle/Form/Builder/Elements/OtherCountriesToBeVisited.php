<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\AllCountry;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class OtherCountriesToBeVisited
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait OtherCountriesToBeVisited
{
    public function otherCountriesToBeVisited(FormBuilderInterface $builder)
    {
        $builder->add('otherCountriesToBeVisited', 'choice', [
            'choices'     => AllCountry::getCountriesArray(),
            'empty_value' => '',
            'multiple'    => true,
            'required'    => false,
            'label'       => '',
            'attr'        => [
                'class'            => 'chosen',
                'data-placeholder' => 'Other countries to be visited on excursions',
            ],
            'label_attr'  => ['class' => 'form-label']
        ]);

        return $this;
    }
}
