<?php

namespace HomeOffice\CtsBundle\Form\Type;

use HomeOffice\CtsBundle\Form\Type\CtsCaseType;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoComCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use HomeOffice\CtsBundle\Form\Type\Components\CorrespondentDetails;
use HomeOffice\CtsBundle\Form\Type\Components\HmpoStandardDetailsType;
use HomeOffice\AlfrescoApiBundle\Service\HmpoPersonTypes;
use HomeOffice\AlfrescoApiBundle\Service\HmpoComplaint;
use HomeOffice\AlfrescoApiBundle\Service\Title;
use HomeOffice\AlfrescoApiBundle\Service\Country;


class CtsHmpoComCaseType extends CtsCaseType
{
    use CorrespondentDetails, HmpoStandardDetailsType;

    /**
     *
     * @param string $formPurpose
     * @param CtsListHandler $ctsListHandler
     * @param CTSHelper $ctsHelper
     * @param bool $cascadeValidation
     */
    public function __construct($formPurpose, $ctsListHandler, $ctsHelper, $cascadeValidation = false)
    {
        parent::__construct($formPurpose, $ctsListHandler, $ctsHelper, $cascadeValidation);
    }

    /**
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $this->buildCorrespondentDetailsForm($builder);
        $this->buildHmpoStandardDetailsForm($builder);
        $hmpoStage = $builder->getData()->getHmpoStage();

        $builder
        ->add('typeOfComplainant', 'choice', array(
            'choices' => HmpoPersonTypes::getHmpoComComplainantTypeArray(),
            'empty_value' => '-'
        ))
        ->add('typeOfRepresentative', 'choice', array(
            'choices' => HmpoPersonTypes::getHmpoComRepresentativeTypeArray(),
            'empty_value' => '-'
        ))
        ->add('replyToComplainant', 'checkbox', array(
            'required' => false,
            'label' => 'Reply to'
        ))
        ->add('complainantTitle', 'choice', array(
            'choices'   => Title::getTitlesArray(),
            'required'  => false,
            'empty_value' => '-',
            'label' => 'Title',
        ))
        ->add('complainantForename', 'text', array(
            'label' => 'Forename',
            'required'  => false,
        ))
        ->add('complainantSurname', 'text', array(
            'required'  => false,
            'label' => 'Surname'
        ))
        ->add('complainantPostcode', 'text', array(
            'required'  => false,
            'label' => 'Postcode'
        ))
        ->add('complainantAddressLine1', 'text', array(
        'attr' => array('placeholder' => 'Address line 1'),
            'label' => 'Address',
            'required'  => false,
        ))
        ->add('complainantAddressLine2', 'text', array(
        'attr' => array('placeholder' => 'Address line 2'),
            'label' => ' ',
            'required'  => false,
        ))
        ->add('complainantAddressLine3', 'text', array(
        'attr' => array('placeholder' => 'Address line 3'),
            'label' => ' ',
            'required'  => false,
        ))
        ->add('complainantCountry', 'choice', array(
            'choices' => Country::getCountriesArray(),
            'label' => 'Country',
        ))
        ->add('complainantTelephone', 'text', array(
            'required'  => false,
            'label' => 'Telephone',
        ))
        ->add('complainantEmail', 'text', array(
            'required'  => false,
            'label' => 'Email',
        ))
        ->add('hmpoStage', 'text', array(
            'required' => false,
            'label' => 'Complaint type',
            'disabled' => true
        ))
        ->add('hmpoRefundDecision', 'choice', array(
            'choices' => HmpoComplaint::getRefundDecisionArray(),
            'empty_value' => '-',
            'label' => 'Refund'
        ))
        ->add('hmpoRefundAmount', 'text', array(
            'required'  => false,
            'label' => 'Amount (£)'
        ))
        ->add('hmpoComplaintOutcome', 'choice', array(
            'choices' => HmpoComplaint::getComplaintOutcomeArray(),
            'multiple' => false,
            'expanded' => true,
            'label' => ''
        ));

        if ($hmpoStage == 'MP complaint') {
            $builder
            ->add('draftResponseTarget', 'date', array(
                'disabled' => true,
                'label' => 'Draft',
            ))
            ->add('dispatchTarget', 'date', array(
                'disabled' => true,
                'label' => 'Dispatch',
            ));
        }
    }

    /**
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoComCase',
            'empty_data' => new CtsHmpoComCase($this->workspace, $this->store),
            'cascade_validation' => $this->cascadeValidation
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'ctsHmpoComCase';
    }
}
