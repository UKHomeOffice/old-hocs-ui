<?php

namespace HomeOffice\CtsBundle\Form\Type\Components;

use Symfony\Component\Form\FormBuilderInterface;

trait ReplyToNumberTenCopy
{

    public function buildReplyToNumberTenCopyForm(FormBuilderInterface $builder)
    {
        $builder
        ->add('replyToNumberTenCopy', 'checkbox', array(
            'required'  => false,
            'label' => 'Reply to No. 10 copy',
        ));
    }
}
