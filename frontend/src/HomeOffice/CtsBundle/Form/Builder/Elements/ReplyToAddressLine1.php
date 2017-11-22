<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReplyToAddressLine1
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReplyToAddressLine1
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function replyToAddressLine1(FormBuilderInterface $builder)
    {
        $builder->add('replyToAddressLine1', 'text', [
            'label'    => 'Address',
            'required' => false,
            'attr' => [
                'placeholder' => 'Address line 1',
            ],
        ]);

        return $this;
    }
}
