<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TertiaryCorrespondentAddressLineTwo
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TertiaryCorrespondentAddressLineTwo
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function tertiaryCorrespondentAddressLineTwo(FormBuilderInterface $builder)
    {
        $builder->add('thirdPartyCorrespondentAddressLine2', 'text', [
            'attr'     => ['placeholder' => 'Address line 2'],
            'label'    => 'Address',
            'required' => false,
        ]);

        return $this;
    }
}
