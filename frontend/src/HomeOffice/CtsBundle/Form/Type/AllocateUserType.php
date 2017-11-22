<?php

namespace HomeOffice\CtsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AllocateUserType extends AbstractType
{
    private $ctsListHandler;
 
    private $unitMap = array();
 
    private $teamMap;
 
    private $peopleMap;
 
    private $isStatusChange;
 
    private $userTeams;
 
    private $userUnits;
 
    public function __construct($ctsListHandler, $userUnits, $userTeams, $isStatusChange = false)
    {
        $this->ctsListHandler = $ctsListHandler;
        $this->isStatusChange = $isStatusChange;
        $this->userTeams = $userTeams;
        $this->userUnits = $userUnits;
    }
 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->ctsListHandler->extractSelectedGroupForPersonQuery($builder->getData());
        $this->setupInitialUnitMapValues();
        $unit = null;
        if ($builder->getData() != null) {
            $unit = $builder->getData()->getAssignedUnit();
        }
     
        $builder
            ->add('id', 'hidden')
            ->add('caseStatus', 'hidden')
            ->add('caseTask', 'hidden')
            ->add('assignedUser', 'hidden')
            ->add('correspondenceType', 'hidden')
            ->add('statusChange', 'hidden', array(
                'data' => $this->isStatusChange,
                'mapped' => false
            ))
         
            ->add('assignedUnit', 'choice', array(
                'choices' => $this->unitMap,
                'empty_value' => 'Please select a unit',
                'label' => 'Unit'
            ))
            ->add('assignedTeam', 'choice', array(
                'choices' => $this->changeTeamMap($unit),
                'empty_value' => 'Please select a team',
                'label' => 'Team'
            ))
            ->add('assignedUser', 'choice', array(
                'choices' => $this->changePeopleMap(),
                'empty_value' => 'Please select a user',
                'label' => 'User'
            ))
            ->add('SetUnit', 'submit', array(
                'label' => 'Select'
            ))
            ->add('SetTeam', 'submit', array(
                'label' => 'Select'
            ))
            ->add('AllocateButton', 'submit', array(
                'label' => 'Allocate'
            ))
            ->add('CancelAllocateButton', 'submit', array(
                'label' => 'Cancel'
            ));
     
        $defaultRadio = "Colleague";
     
        $allocateToArray = count($this->userTeams) < 2 && count($this->userUnits) < 2 ?
                           array('Colleague' => 'Colleague', 'Me' => 'Me') :
                           array($defaultRadio => $defaultRadio);
         
        $builder->add('allocateTo', 'choice', array(
            'choices' => $allocateToArray,
            'multiple' => false,
            'expanded' => true,
            'label' => 'Allocate To',
            'mapped' => false,
            'data' => $defaultRadio,
        ));
    }
 
    public function getName()
    {
        return 'allocation';
    }
 
    /**
     * Setup the values for the unitMap array
     */
    public function setupInitialUnitMapValues()
    {
        foreach ($this->ctsListHandler->getList('ctsUnitAndTeamList') as $unit) {
            $this->unitMap[$unit->getAuthorityName()] = $unit->getDisplayName();
        }
    }
 
    /**
     *
     * @param string $unitAuthorityName
     * @return array $teamMap
     */
    public function changeTeamMap($unitAuthorityName)
    {
        if ($unitAuthorityName != null) {
            foreach ($this->ctsListHandler->getList('ctsUnitAndTeamList') as $unit) {
                if ($unit->getAuthorityName() === $unitAuthorityName) {
                    foreach ($unit->getTeams() as $team) {
                        $this->teamMap[$team->getAuthorityName()] = $team->getDisplayName();
                    }
                }
            }
        }
        return $this->teamMap;
    }
 
    /**
     *
     * @param string $unitAuthorityName
     * @return array $teamMap
     */
    private function changePeopleMap()
    {
        $peopleFromGroup = $this->ctsListHandler->getPeopleFromUnitOrTeam();
        if ($peopleFromGroup != null) {
            foreach ($peopleFromGroup as $person) {
                $this->peopleMap[$person->getUserName()] = $person->getFullName();
            }
        }
        return $this->peopleMap;
    }
 
    public function isStatusChange()
    {
        return $this->isStatusChange;
    }
 
    /**
     * @param boolean $statusChange
     */
    public function setStatusChange($statusChange)
    {
        $this->isStatusChange = $statusChange;
    }
}
