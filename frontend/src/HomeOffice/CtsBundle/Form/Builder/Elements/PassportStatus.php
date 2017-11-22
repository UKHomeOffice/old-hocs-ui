<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PassportStatus
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait PassportStatus
{
    /**
     * @param FormBuilderInterface $builder
     * @return static
     */
    protected function passportStatus(FormBuilderInterface $builder)
    {
        $builder->add(
            'passportStatus',
            'choice',
            [
                'choices'    => [
                    true  => 'Generate passport',
                    false => 'Set to pending'
                ],
                'data'       => true,
                'multiple'   => false,
                'expanded'   => true,
                'attr'       => [
                    'class' => 'inline passportStatusTrigger'
                ],
                'label'      => 'Would you like to generate the passport now?',
                'label_attr' => ['class' => 'block-label']
            ]
        );

        return $this;
    }
}
