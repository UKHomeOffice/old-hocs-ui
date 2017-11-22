<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use Symfony\Component\Form\FormBuilderInterface;

trait HomeSecretaryReply
{

    public function buildHomeSecretaryReplyForm(FormBuilderInterface $builder)
    {
        $builder
        ->add('homeSecretaryReply', 'checkbox', [
            'required' => false,
            'label_attr' => ['class' => 'block-label']
        ]);
    }
}
