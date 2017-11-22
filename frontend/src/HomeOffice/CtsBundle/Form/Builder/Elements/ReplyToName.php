<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReplyToName
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReplyToName
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function replyToName(FormBuilderInterface $builder)
    {
        $builder->add('replyToName', 'text', [
            'label'    => 'Name',
            'required' => false
        ]);

        return $this;
    }
}
