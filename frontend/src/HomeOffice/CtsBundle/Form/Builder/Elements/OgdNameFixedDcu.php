<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class OgdNameFixedDcu
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait OgdNameFixedDcu
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function ogdNameFixedDcu(FormBuilderInterface $builder)
    {
        $builder->add('ogdName', 'hidden', [
            'data' => 'DCU'
        ]);

        return $this;
    }
}
