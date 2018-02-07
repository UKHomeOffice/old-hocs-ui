<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Priority
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait Priority
{
    /**
     * @param FormBuilderInterface $builder
     * @param bool                 $asCheckbox
     *
     * @return static
     */
    public function priority(FormBuilderInterface $builder, $asCheckbox = true)
    {
        if ($asCheckbox == true) {
            $builder->add('priority', 'checkbox', [
                'label' => 'Mark as priority',
            ]);
        } else {
            $builder->add('priority', 'choice', [
                'choices'    => [
                    true  => 'Yes',
                    false => 'No',
                    null    => 'Either',
                ],
                'multiple'   => false,
                'expanded'   => true,
                'attr'       => ['class' => 'inline'],
                'label'      => 'Priority',
                'label_attr' => ['class' => 'block-label']
            ]);
        }

        return $this;
    }
}
