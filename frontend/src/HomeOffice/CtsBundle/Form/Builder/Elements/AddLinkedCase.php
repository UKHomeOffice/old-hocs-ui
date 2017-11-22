<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AddLinkedCase
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait AddLinkedCase
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function addLinkedCase(FormBuilderInterface $builder)
    {
        $builder->add('addLinkedCase', 'submit', [
            'label' => 'Link',
            'attr'  => ['class' => 'button'],
        ]);

        return $this;
    }
}
