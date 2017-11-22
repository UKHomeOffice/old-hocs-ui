<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AppealsCaseToRemove
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait AppealsCaseToRemove
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function appealsCaseToRemove(FormBuilderInterface $builder)
    {
        $builder->add('appealsCaseToRemove', 'hidden', [
            'mapped' => false,
            'attr'   => ['class' => 'appealsCaseToRemove'],
        ]);

        return $this;
    }
}
