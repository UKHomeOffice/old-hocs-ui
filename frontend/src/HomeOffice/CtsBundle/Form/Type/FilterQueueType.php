<?php

namespace HomeOffice\CtsBundle\Form\Type;

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
        ->add('queue', 'hidden', array(
            'data' => $this->queueValue,
        ))
        ->add('status', 'choice', array(
            'label' => 'Status',
            'multiple' => true,
            'choices' => $statusList,
            'attr' => array(
                "class" => "chosen-container-multi",
                "data-placeholder" => "Pick a status filter..."
            )
        ))
        ->add('correspondenceType', 'choice', array(
            'multiple' => true,
            'choices' => $correspondenceMap,
            'empty_value' => 'Select Correspondence',
            'required'  => false,
            'label' => 'Case type',
            'attr' => array(
                "class" => "chosen-container-multi",
                "data-placeholder" => "Pick a correspondence filter..."
            )
        ))
        ->add('task', 'choice', array(
            'label' => 'Task',
            'multiple' => true,
            'choices' =>$taskList,
             'attr' => array(
                 "class" => "chosen-container-multi",
                 "data-placeholder" => "Pick a task filter..."
             )
        ))
        ->add('apply', 'submit', array(
            'label' => 'Apply filters'
        ))
        ->add('exportQueue', 'submit', array(
            'label' => 'Generate report for queue'
        ))
        ->add('clear', 'submit');
     
        if ($this->queueValue == 'teamQueue') {
            $builder
            ->add('team', 'choice', array(
                'label' => 'Team',
                'multiple' => true,
                'choices' => $teamList,
                'attr' => array(
                    "class" => "chosen-container-multi",
                    "data-placeholder" => "Pick a team filter..."
                )
            ))
            ->add('assignedUser', 'choice', array(
                 'label' => 'Assigned user',
                'multiple' => true,
                'choices' => $teamMemberList,
                'attr' => array(
                    "class" => "chosen-container-multi",
                    "data-placeholder" => "Pick a user filter..."
                )
            ));
        }
    }
 
    /**
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false
        ));
    }
 
    public function getName()
    {
        return 'filterQueue';
    }
}
