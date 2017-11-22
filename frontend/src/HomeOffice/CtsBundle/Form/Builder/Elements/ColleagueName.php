<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ColleagueName
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ColleagueName
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function colleagueName(FormBuilderInterface $builder)
    {
        $builder->add('colleagueName', 'text', [
            'label'  => 'Name of colleague',
            'mapped' => false,
        ]);

        return $this;
    }
}
