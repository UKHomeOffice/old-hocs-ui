<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use Symfony\Component\Form\FormBuilderInterface;

trait ReplyToMember
{

    public function buildReplyToMemberForm(FormBuilderInterface $builder)
    {
        $ctsCase = $builder->getData();
        $correspondenceType = '';

        if ($ctsCase != null) {
            $correspondenceType = $ctsCase->getCorrespondenceType();
        }
     
        $memberList = $this->memberList;
        if ($ctsCase != null) {
            $memberList = $this->ctsHelper->handleLegacyValue($this->memberList, $ctsCase->getMember());
        }

        if ($correspondenceType == 'IMCM' || $correspondenceType == 'IMCB') {
            $builder
                ->add('member', 'choice', [
                    'choices' => $memberList,
                    'empty_value' => '',
                    'required' => false,
                    'label' => 'Member *',
                    'attr' => [
                        'class'            => 'chosen',
                        'data-placeholder' => 'Select member',
                    ],
                ]);
        } else {
            $builder
                ->add('member', 'choice', [
                    'choices' => $memberList,
                    'empty_value' => '',
                    'required' => false,
                    'label' => 'Member',
                    'attr' => [
                        'class'            => 'chosen',
                        'data-placeholder' => 'Select member',
                    ],
                ]);
        }
    }
}
