<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SignedByHomeSec
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SignedByHomeSec
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function signedByHomeSec(FormBuilderInterface $builder)
    {
        $builder->add('signedByHomeSec', 'checkbox', [
            'label' => 'Home secretary',
            'disabled' => !in_array($builder->getData()->getCaseStatus(), ['New', 'Draft']),
        ]);

        return $this;
    }
}
