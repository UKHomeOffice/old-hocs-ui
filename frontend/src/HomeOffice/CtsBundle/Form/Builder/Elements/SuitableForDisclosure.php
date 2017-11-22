<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SuitableForDisclosure
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SuitableForDisclosure
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function suitableForDisclosure(FormBuilderInterface $builder)
    {
        $builder->add('suitableForDisclosure', 'choice', [
            'choices'    => [true  => 'Yes', false => 'No', ''    => 'Either'],
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline'],
            'label'      => 'Suitable for disclosure',
            'label_attr' => ['class' => 'block-label']
        ]);

        return $this;
    }
}
