<?php

namespace HomeOffice\CtsBundle\Form\Type;

use HomeOffice\CtsBundle\Form\Type\CtsCaseType;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use HomeOffice\CtsBundle\Form\Type\Components\ReplyToMember;
use HomeOffice\CtsBundle\Form\Type\Components\ReplyToMemberDetails;
use HomeOffice\CtsBundle\Form\Type\Components\StandardDetailsType;
use HomeOffice\CtsBundle\Form\Type\Components\HomeSecretaryReply;

class CtsNo10CaseType extends CtsCaseType
{
    use ReplyToMember,
        ReplyToMemberDetails,
        StandardDetailsType,
        HomeSecretaryReply;
 
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
    }
 
    /**
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case',
            'empty_data' => new CtsNo10Case($this->workspace, $this->store),
            'cascade_validation' => $this->cascadeValidation
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'ctsNo10Case';
    }
}
