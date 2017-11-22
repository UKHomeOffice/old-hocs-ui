<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class HrnsToLink
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait HrnsToLink
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function hrnsToLink(FormBuilderInterface $builder)
    {
        $builder->add('hrnsToLink', 'text', [
            'label' => 'Enter case ID',
        ]);

        return $this;
    }
}
