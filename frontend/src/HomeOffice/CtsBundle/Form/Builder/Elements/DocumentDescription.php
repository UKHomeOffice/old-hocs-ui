<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DocumentDescription
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait DocumentDescription
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function documentDescription(FormBuilderInterface $builder)
    {
        $builder->add('documentDescription', 'textarea', [
            'label'      => 'Description (Optional)',
            'label_attr' => ['class' => 'form-label'],
            'attr'       => [
                'class' => 'form-control form-control-full',
                'rows'  => 4,
            ],
        ]);

        return $this;
    }
}
