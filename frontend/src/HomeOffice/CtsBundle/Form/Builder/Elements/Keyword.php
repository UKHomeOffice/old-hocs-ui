<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Keyword
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait Keyword
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function keyword(FormBuilderInterface $builder)
    {
        $builder->add('keyword', 'text', [
            'label'    => 'Keyword',
        ]);

        return $this;
    }
}