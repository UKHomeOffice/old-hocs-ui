<?php

namespace HomeOffice\CtsBundle\Form\Type;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use HomeOffice\CtsBundle\Form\Type\Components\ReplyToMember;
use HomeOffice\CtsBundle\Form\Type\Components\ReplyToMemberDetails;
use HomeOffice\CtsBundle\Form\Type\Components\StandardDetailsType;
use HomeOffice\CtsBundle\Form\Type\Components\HomeSecretaryReply;
use HomeOffice\CtsBundle\Form\Type\Components\CorrespondentDetails;
use HomeOffice\CtsBundle\Form\Type\Components\ReplyToNumberTenCopy;
use HomeOffice\AlfrescoApiBundle\Service\HmpoResponse;

/**
 * @deprecated
 */
class CtsDcuMinisterialCaseType extends CtsCaseType
{
    use ReplyToMember,
        ReplyToMemberDetails,
        StandardDetailsType,
        HomeSecretaryReply,
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
        $this->buildHomeSecretaryReplyForm($builder);
        $this->buildCorrespondentDetailsForm($builder);
        $this->buildReplyToNumberTenCopyForm($builder);
     
        $builder
            ->add('poTarget', 'date', array(
                'disabled' => true,
                'label' => 'Private Office',
            ))
            ->add('allocateTarget', 'date', array(
                'disabled' => true,
                'label' => 'Allocate',
            ))
            ->add('draftResponseTarget', 'date', array(
                'empty_value' => '-',
                'label' => 'Draft',
                'disabled' => true
            ))
            ->add('dispatchTarget', 'date', array(
                'empty_value' => '-',
                'disabled' => true,
                'label' => 'Dispatch',
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
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase',
            'empty_data' => new CtsDcuMinisterialCase($this->workspace, $this->store),
            'cascade_validation' => $this->cascadeValidation
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'ctsDcuMinisterialCase';
    }
}
