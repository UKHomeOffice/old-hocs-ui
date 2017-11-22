<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class LinkedCaseToRemove
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait LinkedCaseToRemove
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function linkedCaseToRemove(FormBuilderInterface $builder)
    {
        $builder->add('linkedCaseToRemove', 'hidden', [
            'mapped' => false,
            'attr'   => ['class' => 'linkedCaseToRemove'],
        ]);

        return $this;
    }
}
