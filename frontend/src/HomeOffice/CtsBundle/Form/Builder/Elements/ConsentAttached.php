<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ConsentAttached
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ConsentAttached
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function consentAttached(FormBuilderInterface $builder)
    {
        $builder->add('consentAttached', 'checkbox');

        return $this;
    }
}
