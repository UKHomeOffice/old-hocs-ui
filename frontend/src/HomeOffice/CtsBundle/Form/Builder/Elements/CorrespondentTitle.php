<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\Title;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CorrespondentTitle
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CorrespondentTitle
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function correspondentTitle(FormBuilderInterface $builder)
    {
        $builder->add('correspondentTitle', 'choice', [
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
