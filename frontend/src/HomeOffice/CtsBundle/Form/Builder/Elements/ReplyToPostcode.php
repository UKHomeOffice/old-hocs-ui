<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReplyToPostcode
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReplyToPostcode
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function replyToPostcode(FormBuilderInterface $builder)
    {
        $builder->add('replyToPostcode', 'text', [
            'label' => 'Postcode',
        ]);

        return $this;
    }
}
