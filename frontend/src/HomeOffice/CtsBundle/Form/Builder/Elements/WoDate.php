<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class WoDate
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait WoDate
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function woDate(FormBuilderInterface $builder)
    {
        $builder->add('woDate', 'date', [
            'label'       => 'Written order date',
            'attr'        => ['class' => 'datePicker todayButton'],
            'empty_value' => '-',
        ]);

        return $this;
    }
}