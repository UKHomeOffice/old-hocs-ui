<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReviewDate
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReviewDate
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function reviewDate(FormBuilderInterface $builder)
    {
        $builder->add('reviewDate', 'date', [
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton']
        ]);

        return $this;
    }
}
