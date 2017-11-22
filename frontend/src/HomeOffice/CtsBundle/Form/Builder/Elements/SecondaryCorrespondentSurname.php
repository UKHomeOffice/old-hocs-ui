<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondaryCorrespondentSurname
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondaryCorrespondentSurname
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function secondaryCorrespondentSurname(FormBuilderInterface $builder)
    {
        $builder->add('secondaryCorrespondentSurname', 'text', [
            'required' => false,
            'label'    => 'Last name'
        ]);

        return $this;
    }
}
