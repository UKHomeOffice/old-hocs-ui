<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Advice
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait Advice
{
    /**
     * @param FormBuilderInterface $builder
     * @param bool                 $asCheckbox
     *
     * @return static
     */
    public function advice(FormBuilderInterface $builder, $asCheckbox = true)
    {
        if ($asCheckbox == true) {
            $builder->add('advice', 'checkbox', [
                'label' => 'Advice',
            ]);
        } else {
            $builder->add('advice', 'choice', [
                'choices'    => [true  => 'Yes', false => 'No', '' => 'Either'],
                'multiple'   => false,
                'expanded'   => true,
                'attr'       => ['class' => 'inline'],
                'label'      => 'Advice',
                'label_attr' => ['class' => 'block-label']
            ]);
        }

        return $this;
    }
}
