<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ArrivingDateInUK
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ArrivingDateInUK
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function arrivingDateInUK(FormBuilderInterface $builder)
    {
        $builder->add('arrivingDateInUK', 'date', [
            'label'       => 'Date of arrival back in the United Kingdom',
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton futureOnly']
        ]);

        return $this;
    }
}
