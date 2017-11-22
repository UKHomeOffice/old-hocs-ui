<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class RoundRobinAdviceConsultation
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait RoundRobinAdviceConsultation
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function roundRobinAdviceConsultation(FormBuilderInterface $builder)
    {
        $builder->add('roundRobinAdviceConsultation', 'checkbox', [
            'label' => 'Round robin advice',
        ]);

        return $this;
    }
}
