<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CreatedTo
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CreatedTo
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function createdTo(FormBuilderInterface $builder)
    {
        $builder->add('createdTo', 'date', [
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton pastOnly'],
            'label_attr'  => ['class' => 'form-label'],
            'label'       => 'To',
        ]);

        return $this;
    }
}
