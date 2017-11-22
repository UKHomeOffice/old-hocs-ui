<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ApproveOnBehalf
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ApproveOnBehalf
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function approveOnBehalf(FormBuilderInterface $builder)
    {
        $builder->add('approveOnBehalf', 'checkbox', [
            'label'  => 'I am approving on behalf of a colleague',
            'mapped' => false,
        ]);

        return $this;
    }
}
