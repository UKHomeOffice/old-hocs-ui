<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DateOfLetter
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DateOfLetter
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function dateOfLetter(FormBuilderInterface $builder)
    {
        $builder->add('dateOfLetter', 'date', [
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton'],
            'label'       => 'Date of Letter',
        ]);

        return $this;
    }
}
