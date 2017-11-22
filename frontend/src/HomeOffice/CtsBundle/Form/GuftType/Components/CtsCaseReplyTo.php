<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsCaseReplyTo
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\Components
 */
trait CtsCaseReplyTo
{
    /**
     * @param FormBuilderInterface $builder
     */
    public function buildReplyToCaseForm(FormBuilderInterface $builder)
    {
        $builder->add('replyTo', 'CtsCaseReplyTo', [
            'data'   => $builder->getData(),
            'mapped' => false,
        ]);
    }
}
