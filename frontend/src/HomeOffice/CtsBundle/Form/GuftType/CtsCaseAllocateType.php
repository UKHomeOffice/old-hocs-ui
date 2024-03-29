<?php

namespace HomeOffice\CtsBundle\Form\GuftType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\AlfrescoApiBundle\Repository\CtsListsRepository;
use HomeOffice\ListBundle\Service\ListHandler;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsCaseAllocateType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType
 */
class CtsCaseAllocateType extends GuftFormType
{
    /**
     * @var ListHandler
     */
    private $listHandler;

    /**
     * @var CtsListsRepository
     */
    private $listsRepository;

    /**
     * @var Unit[]
     */
    private $unitList;

    /**
     * Constructor
     *
     * @param string             $workspace
     * @param string             $store
     * @param ListHandler        $listHandler
     * @param CtsListsRepository $listsRepository
     */
    public function __construct($workspace, $store, ListHandler $listHandler, CtsListsRepository $listsRepository)
    {
        parent::__construct($workspace, $store);

        $this->listHandler = $listHandler;
        $this->listsRepository = $listsRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('allocateTo', 'choice', [
                'choices' => ['Colleague', 'Me'],
                'multiple' => false,
                'expanded' => true,
                'label' => 'Allocate To',
                'label_attr' => ['class' => 'hidden'],
                'mapped' => false,
            ])
            ->add('assignedUnit', 'choice', [
                'choices' => $this->getUnits(),
                'empty_value' => '',
                'label' => 'Unit',
                'label_attr' => ['class' => 'form-label'],
                'data' => null,
                'attr' => [
                    'class' => 'chosen form-control form-allocate-unit',
                    'data-placeholder' => 'Please select a unit',
                ],
            ])
            ->add('assignedTeam', 'choice', [
                'choices' => [],
                'empty_value' => '',
                'label' => 'Team',
                'label_attr' => ['class' => 'form-label'],
                'data' => null,
                'attr' => [
                    'class' => 'chosen form-control form-allocate-team',
                    'data-placeholder' => 'Please select a team',
                    'disabled' => 'disabled',
                ],
            ])
            ->add('assignedUser', 'choice', [
                'choices' => [],
                'empty_value' => '',
                'label' => 'User',
                'label_attr' => ['class' => 'form-label'],
                'data' => null,
                'attr' => [
                    'class' => 'chosen form-control form-allocate-user',
                    'data-placeholder' => 'Please select a user',
                    'disabled' => 'disabled',
                ],
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event){
            $form = $event->getForm();
            $data = $event->getData();

            if (!is_null($data)) {
                if (isset($data['assignedUnit'])) {
                    $form->add('assignedTeam', 'choice', [
                        'choices' => $this->getTeamsForUnit($data['assignedUnit']),
                    ]);

                    if (isset($data['assignedTeam'])) {
                        $form->add('assignedUser', 'choice', [
                            'choices' => $this->getUsersForTeamOrUnit(
                                array_key_exists('assignedUnit', $data) ? $data['assignedUnit'] : null,
                                array_key_exists('assignedTeam', $data) ? $data['assignedTeam'] : null
                            )
                        ]);
                    }
                }
            }
        });
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsCaseAllocate';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase',
            'empty_data' => new CtsCase($this->workspace, $this->store),
            'attr'       => ['novalidate' => 'novalidate'],
        ]);
    }

    /**
     * Get Units
     *
     * @return array
     */
    private function getUnits()
    {
        $units = [];

        /** @var Unit $unit */
        foreach ($this->getUnitList() as $unit) {
            $units[$unit->getAuthorityName()] = $unit->getDisplayName();
        }

        return $units;
    }

    /**
     * @param $validUnit
     *
     * @return array
     */
    private function getTeamsForUnit($validUnit)
    {
        $teams = [];

        foreach ($this->getUnitList() as $unit) {
            if ($unit->getAuthorityName() === $validUnit) {
                foreach ($unit->getTeams() as $team) {
                    $teams[$team->getAuthorityName()] = $team->getDisplayName();
                }
            }
        }

        return $teams;
    }

    /**
     * Get Users for Team or Unit
     *
     * @param string $unit
     * @param string $team
     *
     * @return array
     */
    private function getUsersForTeamOrUnit($unit, $team)
    {
        $people = [];

        $group = $team ?: $unit;
        foreach ($this->listsRepository->getPeopleFromGroup($group) as $person) {
            $people[$person->getUserName()] = $person->getFullName();
        }

        return $people;
    }

    /**
     * Get Unit List
     *
     * @return Unit[]
     */
    private function getUnitList()
    {
        if (is_null($this->unitList)) {
            $this->unitList = $this->listHandler->getList('ctsUnitAndTeamList');
        }

        return $this->unitList;
    }
}
