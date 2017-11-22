<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Dispatch;

use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DTEN
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Dispatch
 */
class DTEN extends AbstractDispatchType
{
    use Groups\Document;
    use Groups\DocumentRemoval;

    /**
     * @inheritdoc
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
        return 'DTENDispatch';
    }
}
