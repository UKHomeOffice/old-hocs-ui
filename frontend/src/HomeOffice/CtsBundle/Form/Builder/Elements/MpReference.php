<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MpReference
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait MpReference
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function mpReference(FormBuilderInterface $builder)
    {
        $builder->add('mpReference', 'text', [
            'label' => 'MP Reference'
        ]);

        return $this;
    }
}
