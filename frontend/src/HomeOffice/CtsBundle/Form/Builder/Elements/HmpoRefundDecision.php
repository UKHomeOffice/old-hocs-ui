<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\HmpoComplaint;
use Symfony\Component\Form\FormBuilderInterface;

trait HmpoRefundDecision
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function hmpoRefundDecision(FormBuilderInterface $builder)
    {
        $builder->add('hmpoRefundDecision', 'choice', [
            'choices'    => HmpoComplaint::getRefundDecisionArray(),
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline'],
            'label'      => 'Refund type',
            'label_attr' => ['class' => 'block-label']
        ]);

        return $this;
    }
}
