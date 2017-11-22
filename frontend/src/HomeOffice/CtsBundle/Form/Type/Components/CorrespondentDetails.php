<?php

namespace HomeOffice\CtsBundle\Form\Type\Components;

use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Service\Title;
use HomeOffice\AlfrescoApiBundle\Service\Country;

trait CorrespondentDetails
{

    public function buildCorrespondentDetailsForm(FormBuilderInterface $builder)
    {
        $builder
        ->add('correspondentTitle', 'choice', array(
            'choices'   => Title::getTitlesArray(),
            'required'  => false,
            'empty_value' => '-',
            'label' => 'Title',
        ))
        ->add('correspondentForename', 'text', array(
            'label' => 'Forename',
            'required'  => false,
        ))
        ->add('correspondentSurname', 'text', array(
            'required'  => false,
            'label' => 'Surname'
        ))
        ->add('correspondentPostcode', 'text', array(
            'required'  => false,
            'label' => 'Postcode'
        ))
        ->add('correspondentAddressLine1', 'text', array(
        'attr' => array('placeholder' => 'Address line 1'),
            'label' => 'Address',
            'required'  => false,
        ))
        ->add('correspondentAddressLine2', 'text', array(
        'attr' => array('placeholder' => 'Address line 2'),
            'label' => ' ',
            'required'  => false,
        ))
        ->add('correspondentAddressLine3', 'text', array(
        'attr' => array('placeholder' => 'Address line 3'),
            'label' => ' ',
            'required'  => false,
        ))
        ->add('correspondentCountry', 'choice', array(
            'choices' => Country::getCountriesArray(),
            'label' => 'Country',
        ))
        ->add('correspondentTelephone', 'text', array(
            'required'  => false,
            'label' => 'Telephone',
        ))
        ->add('correspondentEmail', 'text', array(
            'required'  => false,
            'label' => 'Email',
        ));
    }
}
