<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Service\CaseStatus;
use HomeOffice\AlfrescoApiBundle\Service\TaskStatus;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FilterQueueType extends AbstractType
{
    /**
     *
     * @var type @var string
     */
    private $queueValue;

    /**
     * @var array
     */
    private $teamsInUnit;

    /**
     * @var array
     */
    private $teamMembers;
    
    public function __construct($queueValue, $teamsInUnit, $teamMembers)
    {
        $this->queueValue = $queueValue;
        $this->teamsInUnit = $teamsInUnit;
        $this->teamMembers = $teamMembers;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $statusList = CaseStatus::getStatusForFilterArray();
        $taskList = TaskStatus::getTasksForFilterArray();
        $teamList = $this->teamsInUnit;
        $teamMemberList = $this->teamMembers;
        $correspondenceMap = CaseCorrespondenceType::getCorrespondenceArrayWithSubTypes();

        $builder
        ->setMethod('get')
        ->add('queue', 'hidden', [
            'data'      => $this->queueValue
        ])
        ->add('correspondenceType', 'choice', [
            'label'     => 'Case type',
            'choices'   => $correspondenceMap,
            'multiple'  => true,
            'expanded'  => false,
            'attr'      => [
                "class"     => "multiselect"
            ]

        ])
        ->add('status', 'choice', [
            'label'     => 'Status',
            'choices'   => $statusList,
            'multiple'  => true,
            'expanded'  => false,
            'attr'      => [
                "class"     => "multiselect"
            ]
        ])

        ->add('task', 'choice', [
            'label'     => 'Task',
            'choices'   => $taskList,
            'multiple'  => true,
            'expanded'  => false,
            'attr'      => [
                "class"     => "multiselect"
            ]
        ])
        ->add('apply', 'submit', [
            'attr'      => ['class' => 'button'],
            'label'      => 'Apply filters'
        ])
        ->add('exportQueue', 'submit', [
            'label'      => 'Generate report for queue',
            'attr'       => ['class' => 'button-tertiary right']
        ])
        ->add('clear', 'submit', ['attr' => ['class' => 'button-secondary']]);

        if ($this->queueValue == 'teamQueue') {
            $builder
            ->add('team', 'choice', [

                'label' => 'Team',
                'multiple' => true,
                'expanded' => false,
                'choices' => $teamList,
                'attr' => [
                    "class" => "multiselect",
                ]
            ])
            ->add('assignedUser', 'choice', [
                 'label' => 'Assigned user',
                'multiple' => true,
                'expanded' => false,
                'choices' => $teamMemberList,
                'attr' => [
                    "class" => "multiselect",
                ]
            ]);
        }
    }
    
    /**
     * 
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }
    
    public function getName()
    {
        return 'filterQueue';
    }
}
