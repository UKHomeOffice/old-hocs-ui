<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Service\Country;

trait ReplyToMemberDetails
{

    public function buildReplyToMemberDetailsForm(FormBuilderInterface $builder)
    {
        $builder
        ->add('replyToName', 'text', [
            'required'  => false,
            'label' => 'Name'
        ])
        ->add('replyToPostcode', 'text', [
            'required'  => false,
            'label' => 'Postcode'
        ])
        ->add('replyToAddressLine1', 'text', [
            'attr' => ['placeholder' => 'Address line 1'],
            'label' => 'Address',
            'required'  => false,
        ])
        ->add('replyToAddressLine2', 'text', [
            'attr' => ['placeholder' => 'Address line 2'],
            'label' => ' ',
            'required'  => false,
        ])
        ->add('replyToAddressLine3', 'text', [
            'attr' => ['placeholder' => 'Address line 3'],
            'label' => ' ',
            'required'  => false,
        ])
        ->add('replyToCountry', 'choice', [
            'choices' => Country::getCountriesArray(),
            'label' => 'Country',
            'attr' => [
                'class' => 'chosen'
            ]
        ])
        ->add('replyToTelephone', 'text', [
            'required'  => false,
            'label' => 'Telephone',
        ])
        ->add('replyToEmail', 'text', [
            'required'  => false,
            'label' => 'Email',
        ]);
    }
}
