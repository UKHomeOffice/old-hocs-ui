<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use Symfony\Component\Form\FormBuilderInterface;

trait ReplyToNumberTenCopy
{

    public function buildReplyToNumberTenCopyForm(FormBuilderInterface $builder)
    {
        $builder
        ->add('replyToNumberTenCopy', 'checkbox', [
            'required'  => false,
            'label' => 'Reply to No. 10 copy',
            'label_attr' => ['class' => 'block-label']
        ]);
    }
}
