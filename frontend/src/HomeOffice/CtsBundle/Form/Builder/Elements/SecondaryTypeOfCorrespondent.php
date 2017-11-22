<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\CorrespondentType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondaryTypeOfCorrespondent
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondaryTypeOfCorrespondent
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    public function secondaryTypeOfCorrespondent(FormBuilderInterface $builder)
    {
        $builder->add('secondaryTypeOfCorrespondent', 'choice', [
            'choices'     => CorrespondentType::getAll(true),
            'empty_value' => '',
            'label'       => 'Type of correspondent',
            'label_attr'  => ['class' => 'form-label'],
            'attr'        => [
                'class'            =>  'chosen',
                'data-placeholder' => 'Select type of correspondent'
            ],
            'disabled'    => method_exists($this, 'isDisabled') ? $this->isDisabled($builder->getData()) : false,
        ]);

        return $this;
    }
}
