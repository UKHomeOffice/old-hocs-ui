<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AcpoConsultation
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait AcpoConsultation
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function acpoConsultation(FormBuilderInterface $builder)
    {
        $builder->add('acpoConsultation', 'checkbox', [
            'label' => 'National Police Chief’s Council (NPCC)',
        ]);

        return $this;
    }
}
