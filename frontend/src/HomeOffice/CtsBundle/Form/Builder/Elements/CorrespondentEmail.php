<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CorrespondentEmail
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CorrespondentEmail
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function correspondentEmail(FormBuilderInterface $builder)
    {
        $builder->add('correspondentEmail', 'text', [
            'required' => false,
            'label'    => 'Email',
        ]);

        return $this;
    }
}
