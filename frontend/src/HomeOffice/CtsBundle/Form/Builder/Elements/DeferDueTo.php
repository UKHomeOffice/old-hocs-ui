<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Service\HmpoResponse as ServiceHmpoResponse;

/**
 * Class DeferDueTo
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DeferDueTo
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function deferDueTo(FormBuilderInterface $builder)
    {
        $builder->add('deferDueTo', 'choice', [
            'label'      => '',
            'choices'    => ['Internal Query' => 'Internal Query', 'External Query' => 'External Query'],
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline'],
            'label_attr' => ['class' => 'block-label inline'],
        ]);

        return $this;
    }
}
