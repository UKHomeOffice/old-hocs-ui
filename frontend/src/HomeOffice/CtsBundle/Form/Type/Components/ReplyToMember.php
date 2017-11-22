<?php

namespace HomeOffice\CtsBundle\Form\Type\Components;

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
                ->add('member', 'choice', array(
                    'choices' => $memberList,
                    'empty_value' => 'Select member',
                    'required'  => false,
                    'label' => 'Member *',
                ));
        } else {
            $builder
                ->add('member', 'choice', array(
                    'choices' => $memberList,
                    'empty_value' => 'Select member',
                    'required'  => false,
                    'label' => 'Member',
                ));
        }
    }
}
