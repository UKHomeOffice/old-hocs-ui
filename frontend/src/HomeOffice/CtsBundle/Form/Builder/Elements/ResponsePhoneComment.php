<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ResponsePhoneComment
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ResponsePhoneComment
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function responsePhoneComment(FormBuilderInterface $builder)
    {
        $builder->add('responsePhoneComment', 'textarea', [
            'required' => false,
            'label'    => 'Comments (optional)',
            'mapped'   => false,
        ]);

        return $this;
    }
}
