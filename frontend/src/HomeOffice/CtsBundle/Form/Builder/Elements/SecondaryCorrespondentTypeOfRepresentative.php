<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\RepresentativeType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondaryCorrespondentTypeOfRepresentative
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondaryCorrespondentTypeOfRepresentative
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function secondaryCorrespondentTypeOfRepresentative(FormBuilderInterface $builder)
    {
        $builder->add('secondaryCorrespondentTypeOfRepresentative', 'choice', [
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
