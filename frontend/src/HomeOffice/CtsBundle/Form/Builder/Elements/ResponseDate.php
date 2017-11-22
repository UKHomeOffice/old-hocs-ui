<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ResponseDate
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ResponseDate
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function responseDate(FormBuilderInterface $builder)
    {
        $builder->add('responseDate', 'date', [
            'label' => 'Date dispatched',
            'empty_value' => '-',
            'attr' => ['class' => 'datePicker todayButton'],
        ]);

        return $this;
    }
}
