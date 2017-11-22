<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DeadlineFrom
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DeadlineFrom
{
    /**
     * @param FormBuilderInterface $builder
     * @param string               $class
     *
     * @return static
     */
    public function deadlineFrom(FormBuilderInterface $builder, $class = 'pastOnly')
    {
        $builder->add('deadlineFrom', 'date', [
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton ' . $class],
            'label_attr'  => ['class' => 'form-label'],
            'label'       => 'From',
        ]);

        return $this;
    }
}
