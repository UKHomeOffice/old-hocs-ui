<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class NslgConsultation
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait NslgConsultation
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function nslgConsultation(FormBuilderInterface $builder)
    {
        $builder->add('nslgConsultation', 'checkbox', [
            'label' => 'National Security Liaison Group (NSLG)',
        ]);

        return $this;
    }
}
