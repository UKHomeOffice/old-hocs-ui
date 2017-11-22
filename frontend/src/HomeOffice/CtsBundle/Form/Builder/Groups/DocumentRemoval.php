<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DocumentRemoval
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait DocumentRemoval
{
    use Elements\RemoveDocument;
    use Elements\DocumentToRemove;

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function documentRemoval(FormBuilderInterface $builder)
    {
        $this
            ->removeDocument($builder)
            ->documentToRemove($builder);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function documentRemovalForm(FormBuilderInterface $builder)
    {
        $builder->add('removeDocument', 'DocumentRemoval', [
            'mapped' => false,
        ]);

        return $this;
    }
}
