<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AddGroupedCase
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait AddGroupedCase
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function addGroupedCase(FormBuilderInterface $builder)
    {
        $builder->add('addGroupedCase', 'submit', [
            'label' => 'Group',
            'attr'  => ['class' => 'button'],
        ]);

        return $this;
    }
}
