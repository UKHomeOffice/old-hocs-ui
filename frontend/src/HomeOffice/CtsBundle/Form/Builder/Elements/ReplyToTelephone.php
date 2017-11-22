<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReplyToTelephone
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReplyToTelephone
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function replyToTelephone(FormBuilderInterface $builder)
    {
        $builder->add('replyToTelephone', 'text', [
            'label' => 'Telephone',
        ]);

        return $this;
    }
}
