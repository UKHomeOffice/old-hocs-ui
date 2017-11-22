<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PassportNumber
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait PassportNumber
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function passportNumber(FormBuilderInterface $builder)
    {
        $builder->add('passportNumber', 'text', [
            'label_attr' => ['class' => 'form-label'],
            'attr'       => ['class' => 'form-control']
        ]);

        return $this;
    }
}
