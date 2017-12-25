<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Approve;

use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class BaseFoi
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Approve
 */
abstract class BaseFoi extends AbstractApproveType
{
    use Groups\Document;
    use Groups\DocumentRemoval;

    /**
     * @inheritdoc
     */
    protected function buildElements(FormBuilderInterface $builder, array $options)
    {
        $this
            ->documentForm($builder)
            ->documentRemoval($builder);
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    protected function getMinisterList()
    {
        return $this->getListService()->getMinisterArray();
    }
}
