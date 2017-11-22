<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PitExtension
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait PitExtension
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function pitExtension(FormBuilderInterface $builder)
    {
        $builder->add('pitExtension', 'choice', [
            'label'      => 'Has the deadline been extended following a public interest extension letter?',
            'choices'    => [true => 'Yes', false => 'No'],
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline publicInterestTestTrigger'],
            'label_attr' => ['class' => 'block-label inline']
        ]);

        return $this;
    }
}
