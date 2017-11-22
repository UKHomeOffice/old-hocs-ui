<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondaryCorrespondentEmail
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondaryCorrespondentEmail
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function secondaryCorrespondentEmail(FormBuilderInterface $builder)
    {
        $builder->add('secondaryCorrespondentEmail', 'text', [
            'required' => false,
            'label'    => 'Email',
            'attr'     => ['placeholder' => 'Email']
        ]);

        return $this;
    }
}
