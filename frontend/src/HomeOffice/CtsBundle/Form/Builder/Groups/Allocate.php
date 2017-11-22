<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\CtsBundle\Form\Builder\Elements\AllocateTo;
use HomeOffice\CtsBundle\Form\Builder\Elements\AssignedTeam;
use HomeOffice\CtsBundle\Form\Builder\Elements\AssignedUnit;
use HomeOffice\CtsBundle\Form\Builder\Elements\AssignedUser;
use HomeOffice\ListBundle\Service\ListService;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Class Allocate
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait Allocate
{
    use AllocateTo;
    use AssignedTeam;
    use AssignedUnit;
    use AssignedUser;

    /**
     * @param FormBuilderInterface $builder
     * @param ListService          $listService
     *
     * @return static
     */
    protected function allocate(FormBuilderInterface $builder, ListService $listService)
    {
        $this
            ->allocateTo($builder)
            ->assignedUnit($builder, $listService->getUnitArray())
            ->assignedTeam($builder)
            ->assignedUser($builder)
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($listService) {
            $form = $event->getForm();
            $data = $event->getData();

            if (!is_null($data)) {
                $form->add('assignedTeam', 'choice', [
                    'choices' => isset($data['assignedUnit']) ? $listService->getTeamArrayForUnit($data['assignedUnit']) : [],
                ]);

                $form->add('assignedUser', 'choice', [
                    'choices' => $listService->getUserArrayForTeamOrUnit(
                        array_key_exists('assignedTeam', $data) ? $data['assignedTeam'] : null,
                        array_key_exists('assignedUnit', $data) ? $data['assignedUnit'] : null
                    )
                ]);
            }
        });

        return $this;
    }
}
