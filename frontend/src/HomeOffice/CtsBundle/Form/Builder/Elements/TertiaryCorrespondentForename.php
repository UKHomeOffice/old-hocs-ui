<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TertiaryCorrespondentForename
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TertiaryCorrespondentForename
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function tertiaryCorrespondentForename(FormBuilderInterface $builder)
    {
        $builder->add('thirdPartyCorrespondentForename', 'text', [
            'label'    => 'First name',
            'required' => false,
        ]);

        return $this;
    }
}
