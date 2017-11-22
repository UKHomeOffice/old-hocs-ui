<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\HmpoComplaint;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class HmpoComplaintOutcome
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait HmpoComplaintOutcome
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $types
     *
     * @return static
     */
    public function hmpoComplaintOutcome(FormBuilderInterface $builder, array $types = [])
    {
        $builder->add('hmpoComplaintOutcome', 'choice', [
            'choices'    => empty($types) ? HmpoComplaint::getComplaintOutcomeArray() : $types,
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline'],
            'label'      => 'Outcome',
            'label_attr' => ['class' => 'block-label inline']
        ]);

        return $this;
    }
}
