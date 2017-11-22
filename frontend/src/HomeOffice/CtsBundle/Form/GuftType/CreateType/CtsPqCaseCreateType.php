<?php

namespace HomeOffice\CtsBundle\Form\GuftType\CreateType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceSubType;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseAllocate;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseGrouped;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseLinked;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseMarkUp;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseReplyTo;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsCaseCreateType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsPqCaseCreateType extends GuftFormType
{
    use CtsCaseTransitions, CtsCaseLinked, CtsCaseGrouped, CtsCaseReplyTo, CtsCaseMarkUp, CtsCaseAllocate;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CtsCase $case */
        $case = $builder->getData();

        $builder
            ->add('uin', 'text', [
                'label'    => 'UIN',
                'required' => false,
                'disabled' => true,
            ])
            ->add('questionNumber', 'text', [
                'label' => 'Question No.',
            ])
            ->add('opDate', 'date', [
                'label'       => 'Order paper date',
                'attr'        => ['class' => 'datePicker todayButton'],
                'empty_value' => '-'
            ])
            ->add('woDate', 'date', [
                'label'       => 'Written order date',
                'attr'        => ['class' => 'datePicker todayButton'],
                'empty_value' => '-'
            ])
            ->add('receivedType', 'choice', [
                'label'      => 'Received',
                'choices'    => ['Direct' => 'Direct', 'Transfer' => 'Transfer'],
                'multiple'   => false,
                'expanded'   => true,
                'attr'       => ['class' => 'receivedTypeTrigger inline'],
                'label_attr' => ['class' => 'block-label inline']
            ])
            ->add('transferDepartmentName', 'text', [
                'label' => 'Department Name'
            ])
            ->add('roundRobin', 'choice', [
                'choices'    => [true => 'Yes', false => 'No'],
                'multiple'   => false,
                'expanded'   => true,
                'attr'       => ['class' => 'roundRobinTrigger inline'],
                'label'      => 'Round Robin',
                'label_attr' => ['class' => 'block-label']
            ])
            ->add('cabinetOfficeGuidance', 'choice', [
                'choices'    => ['Yes' => 'Yes', 'Pending' => 'Pending', 'N/A' => 'N/A'],
                'multiple'   => false,
                'expanded'   => true,
                'attr'       => ['class' => 'inline'],
                'label'      => 'Cabinet Office guidance',
                'label_attr' => ['class' => 'block-label inline']
            ])
            ->add('questionText', 'textarea', [
                'attr'  => [
                    'class' => 'form-control form-control-full',
                    'rows'  => '6',
                ],
            ]);

        if ($this->isEditable($case)) {
            $builder->add('save', 'submit', [
                'attr' => ['class' => 'button button-secondary'],
            ]);
        }

        $builder->add('replyTo', $builder->getData()->getShortName().'ReplyTo', [
            'data'   => $builder->getData(),
            'mapped' => false,
        ]);

        $this->buildTransitionsForm($builder);
        $this->buildLinkedCaseForm($builder);
        $this->buildGroupCaseForm($builder);
        $this->buildMarkUpCaseForm($builder);
        $this->buildAllocateForm($builder);

        $this->applyReadOnly($builder);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase',
            'attr'               => ['novalidate' => 'novalidate'],
            'cascade_validation' => true,
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsPqCaseCreate';
    }
}
