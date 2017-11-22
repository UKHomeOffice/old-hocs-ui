<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondaryCorrespondentForename
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondaryCorrespondentForename
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function secondaryCorrespondentForename(FormBuilderInterface $builder)
    {
        $builder->add('secondaryCorrespondentForename', 'text', [
            'label'    => 'First name',
            'required' => false,
        ]);

        return $this;
    }
}
