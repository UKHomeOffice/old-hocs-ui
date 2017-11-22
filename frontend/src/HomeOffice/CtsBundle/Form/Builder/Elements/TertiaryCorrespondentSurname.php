<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TertiaryCorrespondentSurname
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TertiaryCorrespondentSurname
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function tertiaryCorrespondentSurname(FormBuilderInterface $builder)
    {
        $builder->add('thirdPartyCorrespondentSurname', 'text', [
            'required' => false,
            'label'    => 'Last name'
        ]);

        return $this;
    }
}
