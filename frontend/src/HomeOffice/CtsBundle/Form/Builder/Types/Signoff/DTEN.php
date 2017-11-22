<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Signoff;

use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DTEN
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Signoff
 */
class DTEN extends AbstractSignoffType
{
    use Groups\Document;
    use Groups\DocumentRemoval;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildElements(FormBuilderInterface $builder, array $options)
    {
        $this
            ->documentForm($builder)
            ->documentRemoval($builder)
        ;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'DTENSignoff';
    }
}
