<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class RoyalsConsultation
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait RoyalsConsultation
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function royalsConsultation(FormBuilderInterface $builder)
    {
        $builder->add('royalsConsultation', 'checkbox', [
            'label' => 'Royals',
        ]);

        return $this;
    }
}
