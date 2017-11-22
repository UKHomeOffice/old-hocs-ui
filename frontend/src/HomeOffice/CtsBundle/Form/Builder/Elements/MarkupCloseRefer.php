<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkupCloseRefer
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait MarkupCloseRefer
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function markupCloseRefer(FormBuilderInterface $builder)
    {
        $builder->add('markupCloseRefer', 'submit', [
            'label' => 'Close Case',
            'attr' => ['class' => 'button'],
        ]);
    }
}




