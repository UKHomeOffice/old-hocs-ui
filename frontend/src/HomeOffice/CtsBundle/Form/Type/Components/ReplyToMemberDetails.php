<?php

namespace HomeOffice\CtsBundle\Form\Type\Components;

use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Service\Country;

trait ReplyToMemberDetails
{

    public function buildReplyToMemberDetailsForm(FormBuilderInterface $builder)
    {
        $builder
        ->add('replyToName', 'text', array(
            'required'  => false,
            'label' => 'Name'
        ))
        ->add('replyToPostcode', 'text', array(
            'required'  => false,
            'label' => 'Postcode'
        ))
        ->add('replyToAddressLine1', 'text', array(
            'attr' => array('placeholder' => 'Address line 1'),
            'label' => 'Address',
            'required'  => false,
        ))
        ->add('replyToAddressLine2', 'text', array(
            'attr' => array('placeholder' => 'Address line 2'),
            'label' => ' ',
            'required'  => false,
        ))
        ->add('replyToAddressLine3', 'text', array(
            'attr' => array('placeholder' => 'Address line 3'),
            'label' => ' ',
            'required'  => false,
        ))
        ->add('replyToCountry', 'choice', array(
            'choices' => Country::getCountriesArray(),
            'label' => 'Country',
        ))
        ->add('replyToTelephone', 'text', array(
            'required'  => false,
            'label' => 'Telephone',
        ))
        ->add('replyToEmail', 'text', array(
            'required'  => false,
            'label' => 'Email',
        ));
    }
}
