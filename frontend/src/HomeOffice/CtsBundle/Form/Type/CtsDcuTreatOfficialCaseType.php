<?php

namespace HomeOffice\CtsBundle\Form\Type;

use HomeOffice\CtsBundle\Form\Type\CtsCaseType;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuTreatOfficialCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use HomeOffice\CtsBundle\Form\Type\Components\StandardDetailsType;
use HomeOffice\CtsBundle\Form\Type\Components\CorrespondentDetails;
use HomeOffice\AlfrescoApiBundle\Service\HmpoResponse;
use HomeOffice\CtsBundle\Form\Type\Components\ReplyToNumberTenCopy;

/**
 * @deprecated
 */
class CtsDcuTreatOfficialCaseType extends CtsCaseType
{
    use StandardDetailsType,
        CorrespondentDetails,
        ReplyToNumberTenCopy;
    /**
     *
     * @param string $formPurpose
     * @param ListHandler $ctsListHandler
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
        $this->buildStandardDetailsAndTargetsForm($builder);
        $this->buildCorrespondentDetailsForm($builder);
        $this->buildReplyToNumberTenCopyForm($builder);
     
        $builder
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
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuTreatOfficialCase',
            'empty_data' => new CtsDcuTreatOfficialCase($this->workspace, $this->store),
            'cascade_validation' => $this->cascadeValidation
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'ctsDcuTreatOfficialCase';
    }
}
