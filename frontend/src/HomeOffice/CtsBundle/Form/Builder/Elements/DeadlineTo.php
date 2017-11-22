<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DeadlineTo
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DeadlineTo
{
    /**
     * @param FormBuilderInterface $builder
     * @param string               $class
     *
     * @return static
     */
    public function deadlineTo(FormBuilderInterface $builder, $class = 'pastOnly')
    {
        $builder->add('deadlineTo', 'date', [
            'empty_value' => '-',
            'attr'        => ['class' => 'datePicker todayButton' . $class],
            'label_attr'  => ['class' => 'form-label'],
            'label'       => 'To',
        ]);

        return $this;
    }
}
