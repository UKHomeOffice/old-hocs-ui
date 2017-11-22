<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Keyword
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DeliveryNumber
{
    /**
     * @param  FormBuilderInterface $builder
     * @return static
     */
    protected function deliveryNumber(FormBuilderInterface $builder)
    {
        $builder->add('deliveryNumber', 'text', [
            'label'    => 'Delivery number',
        ]);

        return $this;
    }
}
