<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\ListBundle\Service\ListHandler;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkupMinister
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait AssociatedUnit
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $choices
     *
     * @return $this
     */
    public function associatedUnit(FormBuilderInterface $builder, array $choices)
    {
        $builder->add('associatedUnit', 'choice', [
            'choices'     => $choices,
            'empty_value' => '',
            'required'    => false,
            'label_attr'  => ['class' => 'form-label'],
            'attr'        => [
                'class'            =>  'chosen',
                'data-placeholder' => 'Select unit'
            ]
        ]);

        return $this;
    }
}
