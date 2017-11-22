<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DateReceived
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DateReceived
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function dateReceived(FormBuilderInterface $builder)
    {
        $builder->add('dateReceived', 'date', [
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton pastOnly']
        ]);

        return $this;
    }
}
