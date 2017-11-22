<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Service\IcoOutcome as IcoOutcomeService;

/**
 * Class IcoOutcome
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait IcoOutcome
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function icoOutcome(FormBuilderInterface $builder)
    {
        $builder->add('icoOutcome', 'choice', [
            'choices'     => IcoOutcomeService::getAll(true),
            'empty_value' => '',
            'label'       => 'ICO outcome',
            'label_attr'  => ['class' => 'form-label'],
            'attr'        => [
                'class'            =>  'chosen',
                'data-placeholder' => 'Select ICO Outcome'
            ],
        ]);

        return $this;
    }
}
