<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FoiMinisterSignOff
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait FoiMinisterSignOff
{
    /**
     * @param FormBuilderInterface $builder
     * @param bool                 $asCheckbox
     *
     * @return static
     */
    public function foiMinisterSignOff(FormBuilderInterface $builder, $asCheckbox = true)
    {
        if ($asCheckbox === true) {
            $builder->add('foiMinisterSignOff', 'choice', [
                'label'      => 'Does the response require ministerial sign off?',
                'choices'    => [true => 'Yes', false => 'No'],
                'multiple'   => false,
                'expanded'   => true,
                'attr'       => ['class' => 'inline signOffMinisterTrigger'],
                'label_attr' => ['class' => 'block-label inline']
            ]);
        } else {
            $builder->add('foiMinisterSignOff', 'choice', [
                'choices'    => [true  => 'Yes', false => 'No', ''    => 'Either'],
                'multiple'   => false,
                'expanded'   => true,
                'attr'       => ['class' => 'inline'],
                'label'      => 'Minister sign off',
                'label_attr' => ['class' => 'block-label']
            ]);
        }

        return $this;
    }
}
