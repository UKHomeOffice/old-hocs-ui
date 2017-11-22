<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\Title;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondaryCorrespondentTitle
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondaryCorrespondentTitle
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function secondaryCorrespondentTitle(FormBuilderInterface $builder)
    {
        $builder->add('secondaryCorrespondentTitle', 'choice', [
            'choices'     => Title::getTitlesArray(),
            'required'    => false,
            'empty_value' => '',
            'label'       => 'Title',
            'attr'        => [
                'class'            => 'chosen',
                'data-placeholder' => 'Select title',
            ],
        ]);

        return $this;
    }
}
