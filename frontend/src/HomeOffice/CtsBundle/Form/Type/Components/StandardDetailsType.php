<?php

namespace HomeOffice\CtsBundle\Form\Type\Components;

use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Service\Channel;

trait StandardDetailsType
{

    public function buildStandardDetailsAndTargetsForm(FormBuilderInterface $builder)
    {
        /** @var CtsCase $ctsCase */
        $ctsCase = $builder->getData();
        $correspondenceType = '';

        if ($ctsCase != null) {
            $correspondenceType = $ctsCase->getCorrespondenceType();
        }

        if ($correspondenceType == 'IMCM' || $correspondenceType == 'IMCB') {
            $builder
                ->add('dateOfLetter', 'date', array(
                    'empty_value' => '-',
                    'label' => 'Date of letter*'
                ))
                ->add('channel', 'choice', array(
                    'choices' => Channel::getChannelArray(),
                    'empty_value' => '-',
                    'label' => 'Channel *'
                ));
        } else {
            $builder
                ->add('dateOfLetter', 'date', array(
                    'empty_value' => '-'
                ))
                ->add('channel', 'choice', array(
                    'choices' => Channel::getChannelArray(),
                    'empty_value' => '-'
                ));
        }
        $builder
        ->add('dateReceived', 'date', array(
            'empty_value' => '-'
        ))
        ->add('priority', 'checkbox', array(
            'required' => false
        ))
        ->add('advice', 'checkbox', array(
            'required' => false
        ))
        ->add('mpRef', 'text', array(
            'label' => 'MP ref',
            'required'  => false
        ));
    }
}
