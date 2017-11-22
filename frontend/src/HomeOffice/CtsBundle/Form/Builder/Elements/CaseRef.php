<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CaseRef
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CaseRef
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function caseRef(FormBuilderInterface $builder)
    {
        $builder->add('caseRef', 'text', [
            'label'      => 'Case reference',
            'label_attr' => ['class' => 'form-label'],
            'attr'       => ['class' => 'form-control']
        ]);

        return $this;
    }
}
