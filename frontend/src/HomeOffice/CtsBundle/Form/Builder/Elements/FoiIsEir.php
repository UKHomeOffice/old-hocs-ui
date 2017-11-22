<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FoiIsEir
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait FoiIsEir
{
    /**
     * @param FormBuilderInterface $builder
     * @param bool                 $asCheckbox
     *
     * @return static
     */
    protected function foiIsEir(FormBuilderInterface $builder, $asCheckbox = true)
    {
        if ($asCheckbox === true) {
            $builder->add('foiIsEir', 'choice', [
                'label'      => 'Should this case be handled under the Environmental Information Regulations?',
                'choices'    => [true => 'Yes', false => 'No'],
                'multiple'   => false,
                'expanded'   => true,
                'attr'       => ['class' => 'inline'],
                'label_attr' => ['class' => 'block-label inline']
            ]);
        } else {
            $builder->add('foiIsEir', 'choice', [
                'choices'    => [true  => 'Yes', false => 'No', ''    => 'Either'],
                'multiple'   => false,
                'expanded'   => true,
                'attr'       => ['class' => 'inline'],
                'label'      => 'Is EIR',
                'label_attr' => ['class' => 'block-label']
            ]);
        }

        return $this;
    }
}
