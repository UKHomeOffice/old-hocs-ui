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
trait MarkupUnit
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $choices
     *
     * @return $this
     */
    public function markupUnit(FormBuilderInterface $builder, array $choices, $label = 'Answering Unit')
    {
        $builder->add('markupUnit', 'choice', [
            'choices'     => $choices,
            'empty_value' => '',
            'required'    => false,
            'label'       => $label,
            'label_attr'  => ['class' => 'form-label'],
            'attr'        => [
                'class'            =>  'chosen markup-unit',
                'data-placeholder' => 'Select unit',
            ],
            'disabled'    => method_exists($this, 'isDisabled') ? $this->isDisabled($builder->getData()) : false,
        ]);

        return $this;
    }
}
