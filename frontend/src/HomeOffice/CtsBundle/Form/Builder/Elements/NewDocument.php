<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class NewDocument
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait NewDocument
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function newDocument(FormBuilderInterface $builder)
    {
        $builder->add('newDocument', 'CtsCaseDocument', [
            'data'   => $builder->getData()
        ]);

        return $this;
    }
}
