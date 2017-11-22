<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Priority
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait Amendments
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function amendments(FormBuilderInterface $builder)
    {
        $builder->add(
            'amendments',
            'checkbox',
            [
                'label' => 'Amendments',
                'attr'  => ['class' => 'amendmentsTrigger']
            ]
        );

        return $this;
    }
}
