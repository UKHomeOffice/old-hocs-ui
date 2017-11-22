<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TertiaryCorrespondentPostCode
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TertiaryCorrespondentPostCode
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function tertiaryCorrespondentPostCode(FormBuilderInterface $builder)
    {
        $builder->add('thirdPartyCorrespondentPostcode', 'text', [
            'required' => false,
            'label'    => 'Postcode',
            'attr'     => ['placeholder' => 'Postcode']
        ]);

        return $this;
    }
}
