<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\HmpoComplaint;
use Symfony\Component\Form\FormBuilderInterface;

trait HmpoOutcome
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function hmpoOutcome(FormBuilderInterface $builder)
    {
        $builder->add('hmpoOutcome', 'choice', [
            'choices'    => HmpoComplaint::getComplaintOutcomeArray(),
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline'],
            'label'      => 'Outcome',
            'label_attr' => ['class' => 'block-label']
        ]);

        return $this;
    }
}
