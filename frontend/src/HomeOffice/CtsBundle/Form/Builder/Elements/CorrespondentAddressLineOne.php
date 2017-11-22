<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CorrespondentAddressLine1
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CorrespondentAddressLineOne
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function correspondentAddressLineOne(FormBuilderInterface $builder)
    {
        $builder->add('correspondentAddressLine1', 'text', [
            'label'    => 'Address',
            'required' => false,
        ]);

        return $this;
    }
}
