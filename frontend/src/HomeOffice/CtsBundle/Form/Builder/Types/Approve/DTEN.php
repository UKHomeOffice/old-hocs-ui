<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Approve;

use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DTEN
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Approve
 */
class DTEN extends AbstractApproveType
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
        return 'DTENApprove';
    }
}
