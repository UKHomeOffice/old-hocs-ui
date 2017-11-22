<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DateReceivedFrom
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DateReceivedFrom
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function dateReceivedFrom(FormBuilderInterface $builder)
    {
        $builder->add('dateReceivedFrom', 'date', [
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton pastOnly'],
            'label'       => 'From',
        ]);

        return $this;
    }
}
