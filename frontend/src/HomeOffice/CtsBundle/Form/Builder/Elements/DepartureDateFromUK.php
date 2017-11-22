<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DraftDate
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DepartureDateFromUK
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function departureDateFromUK(FormBuilderInterface $builder)
    {
        $builder->add('departureDateFromUK', 'date', [
            'empty_value' => '-',
            'attr'        => [ 'class' => 'datePicker todayButton futureOnly'],
            'label'       => 'Departure date from the United Kingdom',
            'label_attr'  => ['class' => 'form-label']
        ]);

        return $this;
    }
}
