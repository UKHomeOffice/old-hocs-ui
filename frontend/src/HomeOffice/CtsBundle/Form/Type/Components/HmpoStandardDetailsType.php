<?php

namespace HomeOffice\CtsBundle\Form\Type\Components;

use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Service\Channel;
use HomeOffice\AlfrescoApiBundle\Service\HmpoResponse;
use HomeOffice\AlfrescoApiBundle\Service\HmpoPersonTypes;
use HomeOffice\AlfrescoApiBundle\Service\Title;
use HomeOffice\AlfrescoApiBundle\Service\Country;

trait HmpoStandardDetailsType
{
    public function buildHmpoStandardDetailsForm(FormBuilderInterface $builder)
    {
        $caseType = $builder->getData()->getCorrespondenceType();
        $typeOfCorrespondentArray = array();
        if ($caseType == 'COM') {
            $typeOfCorrespondentArray = HmpoPersonTypes::getHmpoComCorrespondentTypeArray();
        }
        if ($caseType == 'GEN') {
            $typeOfCorrespondentArray = HmpoPersonTypes::getHmpoGenCorrespondentTypeArray();
        }
        $builder
            ->add('dateReceived', 'date', array(
                'empty_value' => '-'
            ))
            ->add('channel', 'choice', array(
                'choices' => Channel::getChannelArray(),
                'empty_value' => '-'
            ))
            ->add('hmpoResponse', 'choice', array(
                'choices' => HmpoResponse::getAll(true),
                'required'  => false,
                'empty_value' => '-',
                'label' => 'Response'
            ))
            ->add('passportNumber', 'text', array(
                'required' => false,
            ))
            ->add('applicationNumber', 'text', array(
                'required' => false,
            ))
            ->add('replyToCorrespondent', 'checkbox', array(
                'required' => false,
                'label' => 'Reply to'
            ))
            ->add('typeOfCorrespondent', 'choice', array(
                'choices' => $typeOfCorrespondentArray,
                'empty_value' => '-',
                'attr' => array(
                    'class' => 'hmpo-type-of-correspondent'
                )
            ))
            ->add('replyToApplicant', 'checkbox', array(
                'required' => false,
                'label' => 'Reply to'
            ))
            ->add('applicantTitle', 'choice', array(
                'choices'   => Title::getTitlesArray(),
                'required'  => false,
                'empty_value' => '-',
                'label' => 'Title',
            ))
            ->add('applicantForename', 'text', array(
                'label' => 'Forename',
                'required'  => false,
            ))
            ->add('applicantSurname', 'text', array(
                'required'  => false,
                'label' => 'Surname'
            ))
            ->add('applicantPostcode', 'text', array(
                'required'  => false,
                'label' => 'Postcode'
            ))
            ->add('applicantAddressLine1', 'text', array(
            'attr' => array('placeholder' => 'Address line 1'),
                'label' => 'Address',
                'required'  => false,
            ))
            ->add('applicantAddressLine2', 'text', array(
            'attr' => array('placeholder' => 'Address line 2'),
                'label' => ' ',
                'required'  => false,
            ))
            ->add('applicantAddressLine3', 'text', array(
            'attr' => array('placeholder' => 'Address line 3'),
                'label' => ' ',
                'required'  => false,
            ))
            ->add('applicantCountry', 'choice', array(
                'choices' => Country::getCountriesArray(),
                'label' => 'Country',
            ))
            ->add('applicantTelephone', 'text', array(
                'required'  => false,
                'label' => 'Telephone',
            ))
            ->add('applicantEmail', 'text', array(
                'required'  => false,
                'label' => 'Email',
            ));
    }
}
