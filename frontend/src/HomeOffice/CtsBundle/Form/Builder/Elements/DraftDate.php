<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DraftDate
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DraftDate
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function draftDate(FormBuilderInterface $builder)
    {
        $builder->add('draftDate', 'date', [
            'label'       => 'Drafting deadline',
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton']
        ]);

        return $this;
    }
}
