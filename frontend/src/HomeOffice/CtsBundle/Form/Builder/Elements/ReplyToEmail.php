<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReplyToEmail
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReplyToEmail
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function replyToEmail(FormBuilderInterface $builder)
    {
        $builder->add('replyToEmail', 'text', [
            'label' => 'Email',
        ]);

        return $this;
    }
}
