<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CabinetOfficeGuidance
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CabinetOfficeGuidance
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function cabinetOfficeGuidance(FormBuilderInterface $builder)
    {
        $builder->add('cabinetOfficeGuidance', 'choice', [
            'choices'    => ['Yes' => 'Yes', 'Pending' => 'Pending', 'N/A' => 'N/A'],
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline'],
            'label'      => 'Cabinet Office guidance',
            'label_attr' => ['class' => 'block-label inline'],
        ]);

        return $this;
    }
}