<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Trait Team
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait Team
{
    /**
     * @inheritdoc
     */
    public function team(FormBuilderInterface $builder, array $choices)
    {
        $builder->add('team', 'choice', [
            'choices'     => array_combine($choices, $choices),
            'empty_value' => '',
            'label'       => 'Team',
            'label_attr'  => ['class' => 'form-label'],
            'attr'        => [
                'class'            =>  'chosen',
                'data-placeholder' => 'Select team'
            ],
            'disabled'    => method_exists($this, 'isDisabled') ? $this->isDisabled($builder->getData()) : false,
        ]);

        return $this;
    }
}
