<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use HomeOffice\CtsBundle\Form\GuftType\Components\ReplyToMember;
use HomeOffice\CtsBundle\Form\GuftType\Components\ReplyToMemberDetails;
use HomeOffice\CtsBundle\Form\GuftType\Components\StandardDetailsType;
use HomeOffice\CtsBundle\Form\GuftType\Components\HomeSecretaryReply;
use HomeOffice\CtsBundle\Form\GuftType\Components\CorrespondentDetails;
use HomeOffice\CtsBundle\Form\GuftType\Components\ReplyToNumberTenCopy;
use HomeOffice\AlfrescoApiBundle\Service\HmpoResponse;

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
            ->add('poTarget', 'date', [
                'disabled' => true,
                'label' => 'Private Office',
            ])
            ->add('allocateTarget', 'date', [
                'disabled' => true,
                'label' => 'Allocate',
            ])
            ->add('draftResponseTarget', 'date', [
                'empty_value' => '-',
                'label' => 'Draft',
                'disabled' => true
            ])
            ->add('dispatchTarget', 'date', [
                'empty_value' => '-',
                'disabled' => true,
                'label' => 'Dispatch',
            ])
            ->add('hmpoResponse', 'choice', [
                'choices' => HmpoResponse::getAll(true),
                'label' => 'Response',
                'empty_value' => '-'
            ]);
    }
 
    /**
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase',
            'empty_data' => new CtsDcuMinisterialCase($this->workspace, $this->store),
            'cascade_validation' => $this->cascadeValidation
        ]);
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
