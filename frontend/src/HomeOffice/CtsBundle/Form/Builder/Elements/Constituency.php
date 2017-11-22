<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Constituency
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait Constituency
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function constituency(FormBuilderInterface $builder)
    {
        $builder->add('constituency', 'text', [
            'required' => false,
            'disabled' => true,
        ]);

        return $this;
    }
}