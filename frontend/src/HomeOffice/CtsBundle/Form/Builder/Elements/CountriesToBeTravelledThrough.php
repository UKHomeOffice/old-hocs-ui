<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\AllCountry;
use Symfony\Component\Form\FormBuilderInterface;

trait CountriesToBeTravelledThrough
{
    public function countriesToBeTravelledThrough(FormBuilderInterface $builder)
    {
        $builder->add('countriesToBeTravelledThrough', 'choice', [
            'choices'     => AllCountry::getCountriesArray(),
            'empty_value' => '',
            'multiple'    => true,
            'required'    => false,
            'label'       => '',
            'attr'        => [
                'class'            => 'chosen',
                'data-placeholder' => 'Countries to be travelled through',
            ],
            'label_attr'  => ['class' => 'form-label']
        ]);

        return $this;
    }
}
