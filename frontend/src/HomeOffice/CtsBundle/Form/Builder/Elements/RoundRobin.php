<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class RoundRobin
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait RoundRobin
{
    /**
     * @param FormBuilderInterface $builder
     * @param bool                 $includeEither
     *
     * @return static
     */
    protected function roundRobin(FormBuilderInterface $builder, $includeEither = false)
    {
        $choices = [true => 'Yes', false => 'No'];
        if ($includeEither === true) {
            $choices[''] = 'Either';
        }

        $builder->add('roundRobin', 'choice', [
            'choices'    => $choices,
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'roundRobinTrigger inline'],
            'label'      => 'Round Robin',
            'label_attr' => ['class' => 'block-label']
        ]);

        return $this;
    }
}
