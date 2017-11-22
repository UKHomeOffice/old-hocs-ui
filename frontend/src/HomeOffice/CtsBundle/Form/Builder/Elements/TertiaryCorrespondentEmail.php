<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TertiaryCorrespondentEmail
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TertiaryCorrespondentEmail
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function tertiaryCorrespondentEmail(FormBuilderInterface $builder)
    {
        $builder->add('thirdPartyCorrespondentEmail', 'text', [
            'required' => false,
            'label'    => 'Email',
            'attr'     => ['placeholder' => 'Email']
        ]);

        return $this;
    }
}
