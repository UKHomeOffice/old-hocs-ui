<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class NewInformationReleased
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait NewInformationReleased
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function newInformationReleased(FormBuilderInterface $builder)
    {
        $builder->add('newInformationReleased', 'checkbox', [
            'label' => 'Additional Information Released',
        ]);

        return $this;
    }
}
