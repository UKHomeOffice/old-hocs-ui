<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class BringUpDate
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait BringUpDate
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function bringUpDate(FormBuilderInterface $builder)
    {
        $builder->add('bringUpDate', 'date', [
            'empty_value' => '-',
            'attr'        => [
                'class'       => 'datePicker todayButton futureOnly'
            ]
        ]);

        return $this;
    }
}
