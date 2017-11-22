<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\CorrespondentType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TertiaryTypeOfCorrespondent
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TertiaryTypeOfCorrespondent
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    public function tertiaryTypeOfCorrespondent(FormBuilderInterface $builder)
    {
        $builder->add('thirdPartyTypeOfCorrespondent', 'choice', [
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
