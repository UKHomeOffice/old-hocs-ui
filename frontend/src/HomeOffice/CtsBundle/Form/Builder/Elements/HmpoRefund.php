<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class HmpoRefund
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait HmpoRefund
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function hmpoRefund(FormBuilderInterface $builder)
    {
        $builder->add('hmpoRefund', 'choice', [
            'label'  => 'Refund awarded?',
            'choices' => [true => 'Yes', false => 'No'],
            'mapped' => false,
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline hmpoRefundTrigger'],
            'label_attr' => ['class' => 'block-label inline'],
            'data'   => $builder->getData()->getHmpoRefundAmount() ? true : false,
        ]);

        return $this;
    }
}
