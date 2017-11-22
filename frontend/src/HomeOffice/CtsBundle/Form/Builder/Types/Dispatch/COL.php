<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Dispatch;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class COL
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Dispatch
 */
class COL extends AbstractDispatchType
{
    use Elements\NewDocument;
    use Elements\DeferDispatch;
    use Elements\BringUpDate;
    use Elements\DispatchedDate;
    use Elements\DeliveryNumber;
    use Elements\Amendments;
    use Elements\Defer;
    use Groups\DocumentRemoval;
    use Groups\Document;

    /**
     * @inheritdoc
     */
    protected function buildElements(FormBuilderInterface $builder, array $options)
    {
        $this
            ->documentForm($builder)
            ->documentRemoval($builder)
            ->deferDispatch($builder)
            ->bringUpDate($builder)
            ->dispatchedDate($builder)
            ->defer($builder)
            ->deliveryNumber($builder)
            ->amendments($builder)
        ;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'COLDispatch';
    }
}
