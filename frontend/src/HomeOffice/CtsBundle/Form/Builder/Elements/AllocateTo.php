<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AllocateTo
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait AllocateTo
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function allocateTo(FormBuilderInterface $builder)
    {
        $builder->add('allocateTo', 'choice', [
            'choices'    => ['Colleague', 'Me'],
            'multiple'   => false,
            'expanded'   => true,
            'label'      => 'Allocate To',
            'label_attr' => ['class' => 'hidden'],
            'mapped'     => false,
        ]);

        return $this;
    }
}
