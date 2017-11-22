<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TertiaryCorrespondentTelephone
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TertiaryCorrespondentTelephone
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function tertiaryCorrespondentTelephone(FormBuilderInterface $builder)
    {
        $builder->add('thirdPartyCorrespondentTelephone', 'text', [
            'required' => false,
            'label'    => 'Telephone',
        ]);

        return $this;
    }
}
