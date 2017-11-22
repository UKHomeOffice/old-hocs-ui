<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsCaseGrouped
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\Components
 */
trait CtsCaseGrouped
{
    /**
     * @param FormBuilderInterface $builder
     */
    public function buildGroupCaseForm(FormBuilderInterface $builder)
    {
        $builder->add('groupedCases', 'CtsCaseGrouped', [
            'label'  => 'Group with another case',
            'data'   => $builder->getData(),
            'mapped' => false,
        ]);
    }
}
