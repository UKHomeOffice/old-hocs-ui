<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UinsToGroup
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait UinsToGroup
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function uinsToGroup(FormBuilderInterface $builder)
    {
        $builder->add('uinsToGroup', 'text', [
            'label' => 'Enter UIN',
            'attr'  => ['class' => 'grouped-uins-class'],
        ]);

        return $this;
    }
}
