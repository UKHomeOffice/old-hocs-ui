<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DeliveryType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DeliveryType
{
    /**
     * @param FormBuilderInterface $builder
     * @return static
     */
    protected function deliveryType(FormBuilderInterface $builder)
    {
        $builder->add('deliveryType', 'choice', [
            'choices'    => [
                'Recorded'             => 'Recorded',
                'Special'              => 'Special',
                'Special (before 9am)' => 'Special (before 9am)'
            ],
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline'],
            'label'      => 'Delivery',
            'label_attr' => ['class' => 'block-label']
        ]);

        return $this;
    }
}
