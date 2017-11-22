<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReplyToAddressLine2
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReplyToAddressLine2
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function replyToAddressLine2(FormBuilderInterface $builder)
    {
        $builder->add('replyToAddressLine2', 'text', [
            'label'    => 'Address',
            'required' => false,
            'attr' => [
                'placeholder' => 'Address line 2',
            ],
        ]);

        return $this;
    }
}
