<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TertiaryCorrespondentAddressLineThree
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TertiaryCorrespondentAddressLineThree
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function tertiaryCorrespondentAddressLineThree(FormBuilderInterface $builder)
    {
        $builder->add('thirdPartyCorrespondentAddressLine3', 'text', [
            'attr'     => ['placeholder' => 'Address line 3'],
            'label'    => 'Address',
            'required' => false,
        ]);

        return $this;
    }
}
