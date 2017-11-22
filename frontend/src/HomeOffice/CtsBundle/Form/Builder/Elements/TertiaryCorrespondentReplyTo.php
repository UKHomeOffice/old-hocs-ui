<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TertiaryCorrespondentReplyTo
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TertiaryCorrespondentReplyTo
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function tertiaryCorrespondentReplyTo(FormBuilderInterface $builder)
    {
        $builder->add('thirdPartyCorrespondentReplyTo', 'checkbox', [
            'label' => 'Reply to',
        ]);

        return $this;
    }
}
