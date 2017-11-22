<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class RemoveGroupedCase
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait RemoveGroupedCase
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function removeGroupedCase(FormBuilderInterface $builder)
    {
        $builder->add('removeGroupedCase', 'submit', [
            'attr' => ['class' => 'hidden removeGroupedCase'],
        ]);

        return $this;
    }
}
