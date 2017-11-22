<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use HomeOffice\AlfrescoApiBundle\Service\Channel;
use Symfony\Component\Form\FormBuilderInterface;

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
                ->add('dateOfLetter', 'date', [
                    'empty_value' => '-',
                    'label' => 'Date of letter*'
                ])
                ->add('channel', 'choice', [
                    'choices' => Channel::getChannelArray(),
                    'empty_value' => '-',
                    'label' => 'Channel *'
                ]);
        } else {
            $builder
                ->add('dateOfLetter', 'date', [
                    'empty_value' => '-'
                ])
                ->add('channel', 'choice', [
                    'choices' => Channel::getChannelArray(),
                    'empty_value' => '-'
                ]);
        }
        $builder
            ->add('dateReceived', 'date', [
                'empty_value' => '-'
            ])
            ->add('priority', 'checkbox', [
                'required' => false,
                'label_attr' => ['class' => 'block-label']
            ])
            ->add('advice', 'checkbox', [
                'required' => false,
                'label_attr' => ['class' => 'block-label']
            ])
            ->add('mpRef', 'text', [
                'label' => 'MP ref',
                'required' => false
            ]);
    }
}
