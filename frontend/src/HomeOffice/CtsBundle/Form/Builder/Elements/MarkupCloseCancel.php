<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkupCloseCancel
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait MarkupCloseCancel
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function markupCloseCancel(FormBuilderInterface $builder)
    {
        $builder->add('markupCloseCancel', 'submit', [
            'label' => 'Close Case',
            'attr' => ['class' => 'button'],
        ]);
    }
}




