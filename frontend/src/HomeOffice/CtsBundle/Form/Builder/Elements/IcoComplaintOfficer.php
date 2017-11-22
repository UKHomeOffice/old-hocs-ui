<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class IcoComplaintOfficer
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait IcoComplaintOfficer
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function icoComplaintOfficer(FormBuilderInterface $builder)
    {
        $builder->add('icoComplaintOfficer', 'text', [
            'label'=> 'ICO contact'
        ]);

        return $this;
    }
}
