<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Uin
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait Uin
{
    /**
     * @param FormBuilderInterface $builder
     * @param bool                 $disabled
     *
     * @return static
     */
    protected function uin(FormBuilderInterface $builder, $disabled = true)
    {
        $builder->add('uin', 'text', [
            'label'    => 'UIN',
            'required' => false,
            'disabled' => $disabled,

        ]);

        return $this;
    }
}
