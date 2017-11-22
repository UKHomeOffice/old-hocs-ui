<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class GroupCase
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait GroupCase
{
    use Elements\UinsToGroup;
    use Elements\AddGroupedCase;
    use Elements\GroupedCaseToRemove;
    use Elements\RemoveGroupedCase;

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function groupCase(FormBuilderInterface $builder)
    {
        $this
            ->uinsToGroup($builder)
            ->addGroupedCase($builder)
            ->groupedCaseToRemove($builder)
            ->removeGroupedCase($builder);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function groupCaseForm(FormBuilderInterface $builder)
    {
        $builder->add('groupedCases', 'GroupCase', [
            'label'  => '',
            'data'   => $builder->getData(),
            'mapped' => false,
        ]);

        return $this;
    }
}
