<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CaseResponseDeadline
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CaseResponseDeadline
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function caseResponseDeadline(FormBuilderInterface $builder, $label = 'Deadline date')
    {
        $builder->add('caseResponseDeadline', 'date', [
            'label'       => $label,
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton futureOnly']
        ]);

        return $this;
    }
}
