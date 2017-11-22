<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondaryCorrespondentReplyTo
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondaryCorrespondentReplyTo
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function secondaryCorrespondentReplyTo(FormBuilderInterface $builder)
    {
        $builder->add('secondaryCorrespondentReplyTo', 'checkbox', [
            'label' => 'Reply to',
        ]);

        return $this;
    }
}
