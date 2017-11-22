<?php

namespace HomeOffice\CtsBundle\Form\Type;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiCase;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\ListBundle\Service\ListHandler;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use HomeOffice\AlfrescoApiBundle\Service\Channel;
use HomeOffice\CtsBundle\Form\Type\Components\CorrespondentDetails;
use HomeOffice\AlfrescoApiBundle\Service\FoiAssess;
/**
 * @deprecated
 */
class CtsFoiCaseType extends CtsCaseType
{
    use CorrespondentDetails;

    /**
     *
     * @var boolean
     */
    private $originalPitExtension;

    /**
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
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $this->buildCorrespondentDetailsForm($builder);

        $foiIsEir = $builder->getData()->getFoiIsEir();

        $builder
            ->add('allocateTarget', 'date', array(
                'disabled' => true,
                'label' => 'Allocate',
            ))
            ->add('draftResponseTarget', 'date', array(
                'disabled' => true,
                'label' => 'Draft response',
            ))
            ->add('scsApprovalTarget', 'date', array(
                'disabled' => true,
                'label' => 'SCS approval',
            ))
            ->add('finalApprovalTarget', 'date', array(
                'disabled' => true,
                'label' => 'Final approval',
            ))
            ->add('dateReceived', 'date', array(
                'empty_value' => '-'
            ))
            ->add('channel', 'choice', array(
                'choices' => Channel::getChannelArray(),
                'empty_value' => '-'
            ))
            ->add('foiMinisterSignOff', 'checkbox', array(
                'required' => false,
                'label' => 'Minister sign-off'
            ))
            ->add('exemptions', 'choice', array(
                'choices' => ($foiIsEir ? FoiAssess::getEirExemptions() : FoiAssess::getFoiExemptions()),
                'multiple' => true,
                'empty_value' => '-',
                'label' => ($foiIsEir ? 'European exemptions for EIR' : 'Exemptions'),
                'attr' => array(
                    "class" => "chosen-container-multi",
                    "data-placeholder" => "Select exemptions..."
                )
            ))
            ->add('foiDisclosure', 'checkbox', array(
                'required' => false,
                'label' => 'Suitable for disclosure'
            ))
            ->add('originalPitExtension', 'hidden', array(
                'data' => $this->originalPitExtension ? 'true' : 'false', 'mapped' => false
            ))
            ->add('pitExtension', 'checkbox', array(
                'required' => false,
                'label' => 'Public interest test extension',
                'attr' => array(
                    'class' => 'foi_pit_extension_checkbox'
                )
            ))
            ->add('pitLetterSentDate', 'date', array(
                'empty_value' => '-',
                'label' => 'Letter sent'
            ))
            ->add('pitQualifiedExemptions', 'choice', array(
                'choices' => $foiIsEir ?
                    FoiAssess::getEirPitQualifiedExemptions() : FoiAssess::getFoiPitQualifiedExemptions(),
                'multiple' => true,
                'empty_value' => '-',
                'label' => 'Qualified exemptions',
                'attr' => array(
                    "class" => "chosen-container-multi",
                    "data-placeholder" => "Select exemptions...",
                    'disabled' => $this->originalPitExtension
                )
            ))
            ->add('acpoConsultation', 'checkbox', array(
                'required' => false,
                'label' => 'ACPO'
            ))
            ->add('cabinetOfficeConsultation', 'checkbox', array(
                'required' => false,
                'label' => 'Cabinet Office'
            ))
            ->add('nslgConsultation', 'checkbox', array(
                'required' => false,
                'label' => 'NSLG'
            ))
            ->add('royalsConsultation', 'checkbox', array(
                'required' => false,
                'label' => 'Royals'
            ))
            ->add('roundRobinAdviceConsultation', 'checkbox', array(
                'required' => false,
                'label' => 'Round robin advice'
            ))
            ->add('hoCaseOfficer', 'text', array(
                'label' => 'HO case officer'
            ))
            ->add('organisation', 'text');

        if ($this->isRequiredTask($builder->getData()->getCaseTask()) ||
            $this->isRequiredStatus($builder->getData()->getCaseStatus())
        ) {
            $this->bindRequiredFormFields($builder);
        }
    }

    /**
     * @return boolean
     */
    public function getOriginalPitExtension()
    {
        return $this->originalPitExtension;
    }

    /**
     * @param boolean $originalPitExtension
     */
    public function setOriginalPitExtension($originalPitExtension)
    {
        $this->originalPitExtension = $originalPitExtension;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiCase',
            'empty_data' => new CtsFoiCase($this->workspace, $this->store),
            'cascade_validation' => $this->cascadeValidation
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ctsFoiCase';
    }
}
