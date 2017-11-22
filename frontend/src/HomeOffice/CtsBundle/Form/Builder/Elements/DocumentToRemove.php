<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DocumentToRemove
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DocumentToRemove
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function documentToRemove(FormBuilderInterface $builder)
    {
        $builder->add('documentToRemove', 'hidden', [
            'mapped' => false,
            'attr'   => ['class' => 'documentToRemove'],
        ]);

        return $this;
    }
}
