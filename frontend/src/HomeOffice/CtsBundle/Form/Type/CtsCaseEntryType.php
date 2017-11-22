<?php

namespace HomeOffice\CtsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;
use HomeOffice\AlfrescoApiBundle\Service\HmpoStages;

class CtsCaseEntryType extends AbstractType
{
 
    /**
     *
     * @var string
     */
    private $correspondenceType;
 
    /**
     *
     * @var string
     */
    private $oldCorrespondenceType;
 
    /**
     *
     * @var string
     */
    private $correspondenceTypeGroup;
 
    /**
     *
     * @param string $correspondenceType
     * @param string $correspondenceGroupType
     */
    public function __construct($correspondenceType = null, $correspondenceGroupType = null)
    {
        $this->correspondenceType = $correspondenceType;
        $this->correspondenceTypeGroup = $correspondenceGroupType;
        $this->oldCorrespondenceType = $correspondenceType;
    }
 
    /**
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $correspondenceMap = CaseCorrespondenceType::getCorrespondenceArrayWithSubTypes();
        $builder
        ->add('correspondenceType', 'choice', array(
            'choices' => $correspondenceMap,
            'empty_value' => '-',
            'data' => $this->correspondenceType,
            'label' => 'Case type',
        ))
        ->add('oldCorrespondenceType', 'hidden', array(
            'mapped' => false,
            'data' => $this->oldCorrespondenceType
        ))
        ->add('SetType', 'submit', array(
            'label' => 'Go'
        ));

        if ($this->correspondenceTypeGroup == 'DCU' ||
            $this->correspondenceTypeGroup == 'FOI' ||
            $this->correspondenceTypeGroup == 'UKVI' ||
            $this->correspondenceTypeGroup == 'HMPO'
        ) {
            $builder
            ->add('dateReceived', 'date', array(
                'empty_value' => '-'
            ))
            ->add('originalDocument', 'file', array(
                'label' => 'Original document',
            ));
        }
        if ($this->correspondenceType == 'DTEN' ||
            $this->correspondenceType == 'UTEN' ||
            $this->correspondenceType == 'FTCI' ||
            $this->correspondenceType == 'FSCI' ||
            $this->correspondenceType == 'FLT' ||
            $this->correspondenceType == 'FUT') {
            $builder
            ->add('caseResponseDeadline', 'date', array(
                'empty_value' => '-',
                'label' => 'Deadline'
            ));
        }
        if ($this->correspondenceType == 'FOI') {
            $builder
            ->add('foiIsEir', 'checkbox', array(
                'required' => false,
                'label' => 'Is EIR?'
            ));
        }
        if ($this->correspondenceTypeGroup == 'PQ') {
            $builder
            ->add('uin', 'text', array(
                'label' => 'UIN ref'
            ))
            ->add('caseResponseDeadline', 'date', array(
                'empty_value' => '-',
                'label' => 'Deadline'
            ))
            ->add('opDate', 'date', array(
                'empty_value' => '-',
                'label' => 'Order Paper date'
            ));
        }
        if ($this->correspondenceType == 'COM') {
            $builder
            ->add('hmpoStage', 'choice', array(
                'choices' => HmpoStages::getHmpoStagesArray(),
                'multiple' => false,
                'expanded' => true,
                'label' => 'Complaint type'
            ));
        }
     
        if (isset($this->correspondenceTypeGroup) && $this->correspondenceTypeGroup != '') {
            $builder
            ->add('Create', 'submit', array(
                'label' => 'Create case'
            ));
        }

    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'ctsCaseEntry';
    }
}
