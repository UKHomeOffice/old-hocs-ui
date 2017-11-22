<?php

namespace HomeOffice\CtsBundle\Form\Type\Components;

use Symfony\Component\Form\FormBuilderInterface;

trait HomeSecretaryReply
{

    public function buildHomeSecretaryReplyForm(FormBuilderInterface $builder)
    {
        $builder
        ->add('homeSecretaryReply', 'checkbox', array(
            'required' => false
        ));
    }
}
