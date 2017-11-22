<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\RepresentativeType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TypeOfRepresentative
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TypeOfRepresentative
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function typeOfRepresentative(FormBuilderInterface $builder)
    {
        $builder->add('typeOfRepresentative', 'choice', [
            'choices'     => RepresentativeType::getAll(true),
            'empty_value' => '',
            'required'    => false,
            'label_attr'  => ['class' => 'form-label'],
            'attr'        => [
                'class'            => 'form-control chosen',
                'data-placeholder' => 'Select type of representative',
            ],
        ]);

        return $this;
    }
}
