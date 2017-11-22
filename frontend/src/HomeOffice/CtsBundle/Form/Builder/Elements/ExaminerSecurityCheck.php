<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Priority
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ExaminerSecurityCheck
{
    /**
     * @param FormBuilderInterface $builder
     * @param bool                 $asCheckbox
     *
     * @return static
     */
    public function examinerSecurityCheck(FormBuilderInterface $builder, $asCheckbox = true)
    {
        $builder->add('examinerSecurityCheck', 'checkbox', [
            'label' => 'Examiner security check',
        ]);

        return $this;
    }
}
