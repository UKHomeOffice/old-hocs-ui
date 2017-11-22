<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReplyToAddressLine3
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReplyToAddressLine3
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function replyToAddressLine3(FormBuilderInterface $builder)
    {
        $builder->add('replyToAddressLine3', 'text', [
            'label'    => 'Address',
            'required' => false,
            'attr' => [
                'placeholder' => 'Address line 3',
            ],
        ]);

        return $this;
    }
}
