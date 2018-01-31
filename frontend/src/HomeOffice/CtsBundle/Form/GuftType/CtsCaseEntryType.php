<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceSubType;
use HomeOffice\AlfrescoApiBundle\Service\HmpoStages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $builder->add('correspondenceType', 'hidden');

        $builder->add('oldCorrespondenceType', 'hidden', [
            'mapped' => false,
            'data'   => $this->oldCorrespondenceType
        ])->add('SetType', 'submit', [
            'label'  => 'Go',
            'attr'   => ['class' => 'initiateCaseSetTypeTarget']
        ]);

        if (
            in_array($this->correspondenceTypeGroup, ['DCU', 'FOI', 'UKVI']) ||
            in_array($this->correspondenceType, ['COM', 'GEN', 'COR'])
        ) {
            $builder->add('dateReceived', 'date', [
                'empty_value' => '-',
                'attr'        => ['class' => 'datePicker todayButton pastOnly'],
                'label_attr'  => ['class' => 'form-label']
            ])->add('originalDocument', 'file', [
                'label'       => 'Original document',
                'attr'        => ['class' => 'hidden'],
                'label_attr'  => ['class' => 'form-label']
            ]);
        }

        if (in_array($this->correspondenceType, ['DTEN', 'UTEN', 'FTCI', 'FSCI', 'FLT', 'FUT'])) {
            $builder->add('caseResponseDeadline', 'date', [
                'empty_value' => '-',
                'label'       => 'Deadline',
                'attr'        => ['class' => 'datePicker todayButton futureOnly'],
                'label_attr'  => ['class' => 'form-label']
            ]);
        }

        if ($this->correspondenceType === 'COR') {
            $builder->add('hmpoCorrespondenceType', 'choice', [
                'choices'     => CaseCorrespondenceSubType::getHmpoCorrespondenceSubTypes(),
                'multiple'    => false,
                'expanded'    => true,
                'label'       => 'Select Correspondence type',
                'label_attr'  => ['class' => 'block-label inline'],
                'mapped'      => false
            ]);
        }

        // @todo Remove OLD HMPO
        if ($this->correspondenceType === 'COM') {
            $builder->add('hmpoStage', 'choice', [
                'choices'     => HmpoStages::getHmpoStagesArray(),
                'empty_value' => '',
                'required'    => false,
                'label'       => 'Task',
                'attr'        => [
                    'class'            => 'chosen',
                    'data-placeholder' => 'Select complaint type',
                ],
                'label_attr'  => ['class' => 'form-label']
            ]);
        }

        if ($this->correspondenceType === 'COL') {
            $builder->add('dateReceived', 'date', [
                'empty_value' => '-',
                'attr'        => ['class' => 'datePicker todayButton pastOnly'],
                'label_attr'  => ['class' => 'form-label']
            ])->add('departureDateFromUK', 'date', [
                'empty_value' => '-',
                'attr'        => [ 'class' => 'datePicker todayButton futureOnly'],
                'label'       => 'Departure date from the United Kingdom',
                'label_attr'  => ['class' => 'form-label']
            ]);
        }

        if (isset($this->correspondenceTypeGroup) && $this->correspondenceTypeGroup !== '') {
            $builder->add('Create', 'submit', [
                'label'       => 'Create',
                'attr'        => ['class' => 'button']
            ]);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ctsCaseEntry';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true
        ]);
    }
}
