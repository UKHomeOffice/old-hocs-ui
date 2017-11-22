<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AppealsCase
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait AppealsCase
{
    use Elements\AppealsToLink;
    use Elements\AddAppeals;
    use Elements\AppealsCaseToRemove;
    use Elements\RemoveAppealsCase;

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function appealsCase(FormBuilderInterface $builder)
    {
        $this
            ->appealsToLink($builder)
            ->addAppeals($builder)
            ->appealsCaseToRemove($builder)
            ->removeAppealsCase($builder)
        ;

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function appealsCaseForm(FormBuilderInterface $builder)
    {
        $builder->add('appealsCases', 'AppealsCase', [
            'label'  => '',
            'data'   => $builder->getData(),
            'mapped' => false,
        ]);

        return $this;
    }
}
