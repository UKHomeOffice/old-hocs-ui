<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Save
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait Save
{
    /**
     * @param FormBuilderInterface $builder
     * @param string               $name
     *
     * @return static
     */
    protected function save(FormBuilderInterface $builder, $name = 'save')
    {
        $builder->add($name, 'submit', [
            'attr' => ['class' => 'button-secondary'],
        ]);

        return $this;
    }
}
