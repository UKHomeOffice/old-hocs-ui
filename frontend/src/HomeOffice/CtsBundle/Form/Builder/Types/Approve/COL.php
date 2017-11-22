<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Approve;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class COL
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Approve
 */
class COL extends AbstractApproveType
{
    use Elements\NewDocument;
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
        ;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'COLApprove';
    }

}
