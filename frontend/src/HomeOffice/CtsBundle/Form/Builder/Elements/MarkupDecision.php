<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Service\MarkupDecisions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class MarkupDecision
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait MarkupDecision
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $markupDecisions
     *
     * @return static
     */
    protected function markupDecision(FormBuilderInterface $builder, array $markupDecisions)
    {
        $case = $builder->getData();

        $builder->add('markupDecision', 'choice', [
            'choices'    => $markupDecisions,
            'label'      => 'Decision',
            'required'   => false,
            'disabled'   => method_exists($this, 'isDisabled') ? $this->isDisabled($builder->getData()) : false,
            'data'       => $case instanceOf CtsCase ? $this->getDefaultValue($case, $markupDecisions) : null,
            'attr'       => [
                'class'            => 'chosen markup-decision',
                'data-placeholder' => 'Select decision'
            ],
            'label_attr' => ['class' => 'form-label'],
        ]);

        if ($case instanceOf CtsCase) {
            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($builder) {
                $form = $event->getForm();
                $data = $event->getData();

                if (array_key_exists('foiIsEir', $data)) {
                    $builder->getData()->setFoiIsEir($data['foiIsEir']);
                }

                $form->add('markupDecision', 'choice', [
                    'choices' => MarkupDecisions::getGuftDecisionList($builder->getData()),
                ]);

            });
        }

        return $this;
    }

    /**
     * @param CtsCase $case
     * @param array   $markupDecisions
     *
     * @return string
     */
    private function getDefaultValue(CtsCase $case, array $markupDecisions)
    {
        return $case->getMarkupDecision() ?: array_values($markupDecisions)[0];
    }
}
