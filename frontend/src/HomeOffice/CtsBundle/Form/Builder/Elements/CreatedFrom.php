<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CreatedFrom
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CreatedFrom
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function createdFrom(FormBuilderInterface $builder)
    {
        $builder->add('createdFrom', 'date', [
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton pastOnly'],
            'label_attr'  => ['class' => 'form-label'],
            'label'       => 'From',
        ]);

        return $this;
    }
}
