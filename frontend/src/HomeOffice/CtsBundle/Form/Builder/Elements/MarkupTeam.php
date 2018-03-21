<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\ListBundle\Service\ListService;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class MarkupTeam
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait MarkupTeam
{
    /**
     * @param FormBuilderInterface $builder
     * @param ListService          $listService
     *
     * @return static
     */
    public function markupTeam(FormBuilderInterface $builder, ListService $listService)
    {
        $builder->add('markupTeam', 'choice', [
            'choices'     => $listService->getTeamArrayForUnit($builder->getData()->getMarkupUnit()),
            'required'    => false,
            'empty_value' => '',
            'label'       => 'Responsible Team',
            'disabled'    => 'disabled',
            'attr'        => [
                'class'            => 'chosen markup-team',
                'data-placeholder' => 'Select team'
            ],
        ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($listService) {
            $form = $event->getForm();
            $data = $event->getData();

            if (!is_null($data)) {
                $form->add('markupTeam', 'choice', [
                    'choices' => $listService->getTeamArrayForUnit($data['markupUnit']),
                ]);
            }
        });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($listService) {
            $form = $event->getForm();
            $data = $event->getData();

            if (!is_null($data) && array_key_exists('markupTeam', $data) === true) {
                $form->add('markupTeam', 'choice', [
                    'choices' => $listService->getTeamArrayForUnit($data['markupTeam']),
                ]);
            }
        });

        return $this;
    }
}
