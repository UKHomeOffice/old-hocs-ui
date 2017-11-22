<?php

namespace HomeOffice\CtsBundle\Form\Type;

use HomeOffice\AlfrescoApiBundle\Service\HmpoResponse;
use HomeOffice\CtsBundle\Form\Type\CtsCaseType;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsUkviCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use HomeOffice\CtsBundle\Form\Type\Components\ReplyToMember;
use HomeOffice\CtsBundle\Form\Type\Components\ReplyToMemberDetails;
use HomeOffice\CtsBundle\Form\Type\Components\StandardDetailsType;
use HomeOffice\CtsBundle\Form\Type\Components\CorrespondentDetails;
use HomeOffice\CtsBundle\Form\Type\Components\ReplyToNumberTenCopy;
use HomeOffice\AlfrescoApiBundle\Service\Title;

/**
 * @deprecated
 */
class CtsUkviCaseType extends CtsCaseType
{
    use ReplyToMember,
        ReplyToMemberDetails,
        StandardDetailsType,
        CorrespondentDetails,
        ReplyToNumberTenCopy;

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
        $this->buildReplyToMemberDetailsForm($builder);
        $this->buildReplyToMemberForm($builder);
        $this->buildStandardDetailsAndTargetsForm($builder);
        $this->buildCorrespondentDetailsForm($builder);
        $this->buildReplyToNumberTenCopyForm($builder);

        $builder
        ->add('caseRef', 'text', array(
            'label' => 'Case ref',
            'required'  => false
        ))
        ->add('thirdPartyCorrespondentTitle', 'choice', array(
            'choices'   => Title::getTitlesArray(),
            'required'  => false,
            'empty_value' => '-',
            'label' => 'Title',
        ))
        ->add('thirdPartyCorrespondentForename', 'text', array(
            'label' => 'Forename',
            'required'  => false
        ))
        ->add('thirdPartyCorrespondentSurname', 'text', array(
            'label' => 'Surname',
            'required'  => false
        ))
        ->add('thirdPartyCorrespondentOrganisation', 'text', array(
            'label' => 'Organisation',
            'required'  => false
        ))
        ->add('thirdPartyCorrespondentTelephone', 'text', array(
            'label' => 'Telephone',
            'required'  => false
        ))
        ->add('thirdPartyCorrespondentEmail', 'text', array(
            'label' => 'Email',
            'required'  => false
        ))
        ->add('thirdPartyCorrespondentPostcode', 'text', array(
            'label' => 'Post code',
            'required'  => false
        ))
        ->add('thirdPartyCorrespondentAddressLine1', 'text', array(
            'attr' => array('placeholder' => 'Address line 1'),
            'label' => 'Address',
            'required'  => false,
        ))
        ->add('thirdPartyCorrespondentAddressLine2', 'text', array(
            'attr' => array('placeholder' => 'Address line 2'),
            'label' => ' ',
            'required'  => false
        ))
        ->add('thirdPartyCorrespondentAddressLine3', 'text', array(
            'attr' => array('placeholder' => 'Address line 3'),
            'label' => ' ',
            'required'  => false
        ))
        ->add('thirdPartyCorrespondentCountry', 'text', array(
            'label' => 'Country',
            'required'  => false
        ))
        ->add('draftResponseTarget', 'date', array(
            'empty_value' => '-',
            'label' => 'Draft',
            'disabled' => true
        ))
        ->add('allocateToResponderTarget', 'date', array(
            'empty_value' => '-',
            'label' => 'Hub',
            'disabled' => true
        ))
        ->add('responderHubTarget', 'date', array(
            'empty_value' => '-',
            'label' => 'Allocate',
            'disabled' => true
        ))
        ->add('hmpoResponse', 'choice', array(
                'choices' => HmpoResponse::getAll(true),
                'label' => 'Response',
                'empty_value' => '-'
        ));
    }

    /**
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsUkviCase',
            'empty_data' => new CtsUkviCase($this->workspace, $this->store),
            'cascade_validation' => $this->cascadeValidation
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'ctsUkviCase';
    }
}
