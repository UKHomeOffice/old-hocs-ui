<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Approve;

use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TRO
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Approve
 */
class TRO extends AbstractApproveType
{
    use Groups\Document;
    use Groups\DocumentRemoval;
    use Groups\Allocate;

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
        return 'TROApprove';
    }
}
