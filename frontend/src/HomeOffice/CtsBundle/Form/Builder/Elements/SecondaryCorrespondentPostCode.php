<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondaryCorrespondentPostCode
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondaryCorrespondentPostCode
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function secondaryCorrespondentPostCode(FormBuilderInterface $builder)
    {
        $builder->add('secondaryCorrespondentPostcode', 'text', [
            'required' => false,
            'label'    => 'Postcode',
            'attr'     => ['placeholder' => 'Postcode']
        ]);

        return $this;
    }
}
