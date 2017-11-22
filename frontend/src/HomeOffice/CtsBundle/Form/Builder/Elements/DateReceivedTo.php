<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DateReceivedTo
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DateReceivedTo
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function dateReceivedTo(FormBuilderInterface $builder)
    {
        $builder->add('dateReceivedTo', 'date', [
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton pastOnly'],
            'label'       => 'To',
        ]);

        return $this;
    }
}
