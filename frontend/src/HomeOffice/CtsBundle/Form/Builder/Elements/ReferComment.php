<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReferComment
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReferComment
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function referComment(FormBuilderInterface $builder)
    {
        $builder->add('referComment', 'textarea', [
            'required' => false,
            'label'    => 'Comments (optional)',
            'mapped'   => false,
        ]);

        return $this;
    }
}
