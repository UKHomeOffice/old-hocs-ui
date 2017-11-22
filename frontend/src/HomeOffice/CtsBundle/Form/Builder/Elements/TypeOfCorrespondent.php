<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\CorrespondentType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TypeOfCorrespondent
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TypeOfCorrespondent
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    public function typeOfCorrespondent(FormBuilderInterface $builder)
    {
        $builder->add('typeOfCorrespondent', 'choice', [
            'choices'     => CorrespondentType::getAll(true),
            'empty_value' => '',
            'label_attr'  => ['class' => 'form-label'],
            'attr'        => [
                'class'            => 'chosen',
                'data-placeholder' => 'Select type of correspondent'
            ],
            'disabled'    => method_exists($this, 'isDisabled') ? $this->isDisabled($builder->getData()) : false,
        ]);

        return $this;
    }
}
