<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class HOCaseOfficer
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait HOCaseOfficer
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function hoCaseOfficer(FormBuilderInterface $builder)
    {
        $builder->add('hoCaseOfficer', 'text', [
            'label'      => 'HO case officer',
            'label_attr' => ['class' => 'form-label'],
        ]);

        return $this;
    }
}
