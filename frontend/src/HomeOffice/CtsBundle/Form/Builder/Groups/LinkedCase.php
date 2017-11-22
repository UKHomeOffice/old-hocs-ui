<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class LinkedCase
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait LinkedCase
{
    use Elements\HrnsToLink;
    use Elements\AddLinkedCase;
    use Elements\LinkedCaseToRemove;
    use Elements\RemoveLinkedCase;

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function linkedCase(FormBuilderInterface $builder)
    {
        $this
            ->hrnsToLink($builder)
            ->addLinkedCase($builder)
            ->linkedCaseToRemove($builder)
            ->removeLinkedCase($builder);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function linkedCaseForm(FormBuilderInterface $builder)
    {
        $builder->add('linkedCases', 'LinkedCase', [
            'label'  => '',
            'data'   => $builder->getData(),
            'mapped' => false,
        ]);

        return $this;
    }
}
