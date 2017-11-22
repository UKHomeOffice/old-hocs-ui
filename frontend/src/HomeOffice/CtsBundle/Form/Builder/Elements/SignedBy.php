<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SignedBy
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SignedBy
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function signedBy(FormBuilderInterface $builder)
    {
        $builder->add('signedBy', 'choice', [
            'choices'    => [
                'Home secretary' => 'Home secretary',
                '' => 'Any',
            ],
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline'],
            'label'      => 'Signed By',
            'label_attr' => ['class' => 'block-label']
        ]);

        return $this;
    }
}
