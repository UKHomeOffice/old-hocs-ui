<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DateReceived
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait HardCopyReceived
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function hardCopyReceived(FormBuilderInterface $builder)
    {
        $builder->add('hardCopyReceived', 'date', [
            'empty_value' => '-',
            'attr'        => [
                'class'       => 'datePicker todayButton pastOnly'
            ]
        ]);

        return $this;
    }
}
