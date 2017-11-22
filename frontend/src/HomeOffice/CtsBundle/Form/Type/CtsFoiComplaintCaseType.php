<?php

namespace HomeOffice\CtsBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\CtsBundle\Form\Type\Components\CorrespondentDetails;
use HomeOffice\AlfrescoApiBundle\Service\FoiComplaint;

class CtsFoiComplaintCaseType extends CtsCaseType
{
    use CorrespondentDetails;

    /**
     *
     * @var boolean
     */
    private $originalComplex;

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
        $this->buildCorrespondentDetailsForm($builder);

        $correspondenceType = $options['data']->getCorrespondenceType();

        $builder
            ->add('correspondenceType', 'text', array(
                'disabled' => true,
                'label' => 'Case type',
            ))
            ->add('dateReceived', 'date', array(
                'empty_value' => '-'
            ))

            ->add('responseDate', 'date', array(
                'empty_value' => '-'
            ))
            ->add('hoCaseOfficer', 'text', array(
                'label' => 'HO case officer'
            ))
            ->add('organisation', 'text');

        if ($correspondenceType == 'FLT' || $correspondenceType == 'FUT') { // tribunal
            $builder
                ->add('tsolRep', 'text', array(
                    'label' => 'TSOL rep'
                ))
                ->add('appellant', 'choice', array(
                    'choices' => FoiComplaint::getAppellantArray(),
                    'empty_value' => '-',
                ))
                ->add('hoJoined', 'checkbox', array(
                    'label' => 'HO Joined'
                ))
                ->add('tribunalOutcome', 'choice', array(
                    'choices' => FoiComplaint::getOutcomeArray(),
                    'empty_value' => '-',
                ))
                ->add('tribunalOutcomeDate', 'date', array(
                    'empty_value' => '-'
                ))
                ->add('caseResponseDeadline', 'date', array(
                    'disabled' => true
                ));
        }

        if ($correspondenceType == 'FSC') { // internal substantive
            $builder
                ->add('originalComplex', 'hidden', array(
                    'data' => $this->originalComplex ? 'true' : 'false', 'mapped' => false
                ))
                ->add('complex', 'checkbox', array(
                    'required' => false,
                    'label' => 'Complex',
                ))
                ->add('newInformationReleased', 'checkbox');
        }

        if ($correspondenceType == 'FTCI') { // ICO time complaint
            $builder
                ->add('enforcementNoticeNeeded', 'checkbox', array(
                    'label' => 'Enforcement / information notice needed'
                ))
                ->add('enforcementNoticeDeadline', 'date', array(
                    'empty_value' => '-'
                ));
        }

        if ($correspondenceType == 'FSCI') { // ICO substantive complaint
            $builder
                ->add('icoComplaintOfficer', 'text', array(
                    'label'=> 'ICO complaint officer'
                ))
                ->add('icoOutcome', 'choice', array(
                    'label' => 'ICO outcome',
                    'choices' => FoiComplaint::getOutcomeArray(),
                    'empty_value' => '-',
                ))
                ->add('icoOutcomeDate', 'date', [
                    'empty_value' => '-',
                    'label' => 'ICO outcome date'
                ]);
        }

        if ($correspondenceType == 'FTCI' || $correspondenceType == 'FSCI') {
            $builder
                ->add('icoReference', 'text', array(
                    'label' => 'ICO reference'
                ));
        }
    }

    /**
     *
     * @return boolean
     */
    public function getOriginalComplex()
    {
        return $this->originalComplex;
    }

    /**
     *
     * @param boolean $originalComplex
     */
    public function setOriginalComplex($originalComplex)
    {
        $this->originalComplex = $originalComplex;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'ctsFoiComplaintCase';
    }
}
