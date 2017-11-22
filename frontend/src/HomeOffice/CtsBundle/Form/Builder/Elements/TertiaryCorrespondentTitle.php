<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\Title;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TertiaryCorrespondentTitle
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TertiaryCorrespondentTitle
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function tertiaryCorrespondentTitle(FormBuilderInterface $builder)
    {
        $builder->add('thirdPartyCorrespondentTitle', 'choice', [
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
