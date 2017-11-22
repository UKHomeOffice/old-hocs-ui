<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class OgdName
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait OgdName
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function ogdName(FormBuilderInterface $builder)
    {
        $builder->add('ogdName', 'text', [
            'label' => 'Department name',
        ]);

        return $this;
    }
}
