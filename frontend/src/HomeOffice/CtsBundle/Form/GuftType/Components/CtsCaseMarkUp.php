<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsCaseMarkUp
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\Components
 */
trait CtsCaseMarkUp
{
    /**
     * @param FormBuilderInterface $builder
     */
    public function buildMarkUpCaseForm(FormBuilderInterface $builder)
    {
        $builder->add('markUp', 'CtsCaseMarkUp', [
            'label'  => '',
            'data'   => $builder->getData(),
            'mapped' => false,
        ]);
    }
}
