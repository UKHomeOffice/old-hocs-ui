<?php

namespace HomeOffice\CtsBundle\Form\Type;

use HomeOffice\CtsBundle\Form\Type\CtsCaseType;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoComCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use HomeOffice\CtsBundle\Form\Type\Components\CorrespondentDetails;
use HomeOffice\CtsBundle\Form\Type\Components\HmpoStandardDetailsType;
use HomeOffice\AlfrescoApiBundle\Service\HmpoPersonTypes;

class CtsHmpoGenCaseType extends CtsCaseType
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
     
        $builder
            ->add('typeOfThirdParty', 'choice', array(
                'choices' => HmpoPersonTypes::getHmpoGenThirdPartyTypeArray(),
                'empty_value' => '-'
            ))
            ->add('consentAttached', 'checkbox', array(
                'required' => false
            ));
    }
 
    /**
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoGenCase',
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
        return 'ctsHmpoGenCase';
    }
}
