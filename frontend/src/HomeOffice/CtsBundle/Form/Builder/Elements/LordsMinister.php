<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\PQLordsMinister;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class LordsMinister
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait LordsMinister
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $choices
     *
     * @return static
     */
    public function lordsMinister(FormBuilderInterface $builder, array $choices = [])
    {
        $builder->add('lordsMinister', 'choice', [
            'label' => 'Lord\'s Minister',
            'empty_value' => '',
            'choices' => empty($choices) ? PQLordsMinister::getPQLordsMinisterArray() : $choices,
            'disabled' => $builder->getData()->getCaseTask() !== 'Parly approval',
            'attr'        => [
                'class'            => 'chosen',
                'data-placeholder' => 'Select Lord\'s Minister',
            ],
        ]);

        return $this;
    }
}
