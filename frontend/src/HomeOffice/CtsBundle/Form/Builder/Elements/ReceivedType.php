<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReceivedType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReceivedType
{
    /**
     * @param FormBuilderInterface $builder
     * @param bool                 $includeEither
     *
     * @return static
     */
    protected function receivedType(FormBuilderInterface $builder, $includeEither = false)
    {
        $choices = [
            'Direct'   => 'Direct',
            'Transfer' => 'Transfer'
        ];
        if ($includeEither === true) {
            $choices[''] = 'Either';
        }

        $builder->add('receivedType', 'choice', [
            'label'      => 'Received',
            'choices'    => $choices,
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'receivedTypeTrigger inline'],
            'label_attr' => ['class' => 'block-label inline']
        ]);

        return $this;
    }
}
