<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class HomeSecretaryReply
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait HomeSecretaryReply
{
    /**
     * @param FormBuilderInterface $builder
     * @param bool                 $asCheckbox
     *
     * @return static
     */
    public function homeSecretaryReply(FormBuilderInterface $builder, $asCheckbox = true)
    {
        if ($asCheckbox == true) {
            $builder->add('homeSecretaryReply', 'checkbox');
        } else {
            $builder->add('homeSecretaryReply', 'choice', [
                'choices'    => [
                    true  => 'Yes',
                    false => 'No',
                    ''    => 'Either',
                ],
                'multiple'   => false,
                'expanded'   => true,
                'attr'       => ['class' => 'inline'],
                'label_attr' => ['class' => 'block-label'],
                'label'      => 'Home Secretary reply'
            ]);
        }

        return $this;
    }
}
