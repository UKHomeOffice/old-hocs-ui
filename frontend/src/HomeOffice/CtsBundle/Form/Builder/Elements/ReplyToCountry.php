<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\Country;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReplyToCountry
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReplyToCountry
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function replyToCountry(FormBuilderInterface $builder)
    {
        $builder->add('replyToCountry', 'choice', [
            'choices' => Country::getCountriesArray(),
            'label'   => 'Country',
            'attr' => [
                'class' => 'chosen',
            ],
        ]);

        return $this;
    }
}
