<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CaseId
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CaseId
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function caseId(FormBuilderInterface $builder)
    {
        $builder->add('caseId', 'text', [
            'label'      => 'Case reference',
            'label_attr' => ['class' => 'form-label'],
            'attr'       => ['class' => 'form-control']
        ]);

        return $this;
    }
}
