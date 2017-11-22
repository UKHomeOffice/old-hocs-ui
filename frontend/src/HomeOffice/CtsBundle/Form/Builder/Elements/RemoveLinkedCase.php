<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class RemoveLinkedCase
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait RemoveLinkedCase
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function removeLinkedCase(FormBuilderInterface $builder)
    {
        $builder->add('removeLinkedCase', 'submit', [
            'attr' => ['class' => 'hidden removeLinkedCase'],
        ]);

        return $this;
    }
}
