<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class OpDate
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait OpDate
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function opDate(FormBuilderInterface $builder)
    {
        $builder->add('opDate', 'date', [
            'label'       => 'Order paper date',
            'attr'        => ['class' => 'datePicker todayButton'],
            'empty_value' => '-',
        ]);

        return $this;
    }
}