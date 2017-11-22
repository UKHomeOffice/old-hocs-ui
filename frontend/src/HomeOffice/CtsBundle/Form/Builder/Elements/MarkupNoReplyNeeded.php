<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkupNoReplyNeeded
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait MarkupNoReplyNeeded
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function MarkupNoReplyNeeded(FormBuilderInterface $builder)
    {
        $builder->add('NoReplyNeeded', 'MarkupNoReplyNeeded', [
            'label'  => '',
            'data'   => $builder->getData(),
            'mapped' => false,
        ]);

        return $this;
    }
}
