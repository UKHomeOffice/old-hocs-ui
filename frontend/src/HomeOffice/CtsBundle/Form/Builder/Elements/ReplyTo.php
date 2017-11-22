<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReplyTo
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReplyTo
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function replyTo(FormBuilderInterface $builder)
    {
        $builder->add('replyTo', 'checkbox');

        return $this;
    }
}
