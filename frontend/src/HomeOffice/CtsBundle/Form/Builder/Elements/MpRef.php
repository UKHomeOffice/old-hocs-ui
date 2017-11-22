<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MpRef
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait MpRef
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function mpRef(FormBuilderInterface $builder)
    {
        $builder->add('mpRef', 'text', [
            'label' => 'MP reference',
        ]);

        return $this;
    }
}
