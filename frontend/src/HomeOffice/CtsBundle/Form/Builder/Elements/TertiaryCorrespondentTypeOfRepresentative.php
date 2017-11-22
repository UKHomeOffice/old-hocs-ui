<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\RepresentativeType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TertiaryCorrespondentTypeOfRepresentative
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TertiaryCorrespondentTypeOfRepresentative
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function tertiaryCorrespondentTypeOfRepresentative(FormBuilderInterface $builder)
    {
        $builder->add('thirdPartyCorrespondentTypeOfRepresentative', 'choice', [
            'choices'     => RepresentativeType::getAll(true),
            'empty_value' => '',
            'required'    => false,
            'label'       => 'Type of representative',
            'label_attr'  => ['class' => 'form-label'],
            'attr'        => [
                'class'            => 'form-control chosen',
                'data-placeholder' => 'Select type of representative',
            ],
        ]);

        return $this;
    }
}
