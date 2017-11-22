<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Dispatch;

use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MIN
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Dispatch
 */
class MIN extends AbstractDispatchType
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
        return 'MINDispatch';
    }
}
