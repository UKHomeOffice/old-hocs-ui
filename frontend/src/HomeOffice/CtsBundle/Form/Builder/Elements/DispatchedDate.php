<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DateReceived
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DispatchedDate
{
    /**
     * @param  FormBuilderInterface $builder
     * @return static
     */
    protected function dispatchedDate(FormBuilderInterface $builder)
    {
        $builder->add('dispatchedDate', 'date', [
            'label'       => 'Date dispatched',
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton']
        ]);

        return $this;
    }
}
