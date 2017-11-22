<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondaryCorrespondentTelephone
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondaryCorrespondentTelephone
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function secondaryCorrespondentTelephone(FormBuilderInterface $builder)
    {
        $builder->add('secondaryCorrespondentTelephone', 'text', [
            'required' => false,
            'label'    => 'Telephone',
        ]);

        return $this;
    }
}
