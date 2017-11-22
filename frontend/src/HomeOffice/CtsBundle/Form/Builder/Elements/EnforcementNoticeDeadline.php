<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class EnforcementNoticeDeadline
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait EnforcementNoticeDeadline
{
    /**
     * @param  FormBuilderInterface $builder
     *
     * @return static
     */
    protected function enforcementNoticeDeadline(FormBuilderInterface $builder)
    {
        $builder->add('enforcementNoticeDeadline', 'date', [
            'label' => 'Enforcement/Information notice deadline',
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton'],
        ]);

        return $this;
    }
}
