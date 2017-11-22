<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class HmpoRefundAmount
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait HmpoRefundAmount
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function hmpoRefundAmount(FormBuilderInterface $builder)
    {
        $builder->add('hmpoRefundAmount', 'text', [
            'label'      => 'Amount (£)',
            'label_attr' => ['class' => 'form-label'],
            'attr'       => ['class' => 'form-control']
        ]);

        return $this;
    }
}
