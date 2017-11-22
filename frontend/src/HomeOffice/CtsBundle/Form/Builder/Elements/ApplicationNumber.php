<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ApplicationNumber
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ApplicationNumber
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function applicationNumber(FormBuilderInterface $builder)
    {
        $builder->add('applicationNumber', 'text', [
            'label_attr' => ['class' => 'form-label'],
            'attr'       => ['class' => 'form-control']
        ]);

        return $this;
    }
}
