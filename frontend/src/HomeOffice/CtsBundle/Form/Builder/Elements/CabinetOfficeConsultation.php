<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CabinetOfficeConsultation
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CabinetOfficeConsultation
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function cabinetOfficeConsultation(FormBuilderInterface $builder)
    {
        $builder->add('cabinetOfficeConsultation', 'checkbox', [
            'label' => 'Cabinet Office (clearing house)',
        ]);

        return $this;
    }
}
