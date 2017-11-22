<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsCaseLinked
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\Components
 */
trait CtsCaseLinked
{
    /**
     * @param FormBuilderInterface $builder
     */
    public function buildLinkedCaseForm(FormBuilderInterface $builder)
    {
        $builder->add('linkedCases', 'CtsCaseLinked', [
            'label'  => 'Link to another case',
            'data'   => $builder->getData(),
            'mapped' => false,
        ]);
    }
}
