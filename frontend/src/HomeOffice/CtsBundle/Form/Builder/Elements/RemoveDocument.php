<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class RemoveDocument
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait RemoveDocument
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function removeDocument(FormBuilderInterface $builder)
    {
        $builder->add('removeDocument', 'submit', [
            'attr' => ['class' => 'hidden removeDocument'],
        ]);

        return $this;
    }
}
