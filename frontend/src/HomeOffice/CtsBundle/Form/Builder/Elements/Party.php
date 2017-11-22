<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Party
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait Party
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function party(FormBuilderInterface $builder)
    {
        $builder->add('party', 'text', [
            'required' => false,
            'disabled' => true,
        ]);

        return $this;
    }
}