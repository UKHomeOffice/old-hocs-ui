<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TertiaryCorrespondentAddressLineOne
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TertiaryCorrespondentAddressLineOne
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function tertiaryCorrespondentAddressLineOne(FormBuilderInterface $builder)
    {
        $builder->add('thirdPartyCorrespondentAddressLine1', 'text', [
            'attr'     => ['placeholder' => 'Address line 1'],
            'label'    => 'Address',
            'required' => false,
        ]);

        return $this;
    }
}
