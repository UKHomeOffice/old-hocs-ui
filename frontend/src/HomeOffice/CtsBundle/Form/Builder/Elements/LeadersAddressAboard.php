<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class LeadersAddressAboard
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait LeadersAddressAboard
{
    /**
     * @param FormBuilderInterface $builder
     * @param bool                 $asCheckbox
     *
     * @return static
     */
    public function leadersAddressAboard(FormBuilderInterface $builder, $asCheckbox = true)
    {
        $builder->add('leadersAddressAboard', 'textarea', [
            'label'      => 'Leader\'s address abroad',
            'label_attr' => ['class' => 'form-label'],
            'attr'       => [
                'class' => 'form-control form-control-full',
                'rows'  => 4,
            ],
        ]);

        return $this;
    }
}
