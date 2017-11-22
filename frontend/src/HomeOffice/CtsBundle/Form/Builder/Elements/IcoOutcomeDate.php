<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class IcoOutcomeDate
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait IcoOutcomeDate
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function icoOutcomeDate(FormBuilderInterface $builder)
    {
        $builder->add('icoOutcomeDate', 'date', [
            'empty_value' => '-',
            'label' => 'ICO outcome date',
            'attr' => ['class' => 'datePicker todayButton pastOnly'],
        ]);

        return $this;
    }
}
