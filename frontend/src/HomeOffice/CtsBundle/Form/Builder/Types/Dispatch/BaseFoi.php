<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Dispatch;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class BaseFoi
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Dispatch
 */
abstract class BaseFoi extends AbstractDispatchType
{
    use Groups\Document;
    use Elements\ResponseDate;

    /**
     * @inheritdoc
     */
    protected function buildElements(FormBuilderInterface $builder, array $options)
    {
        $this
            ->documentForm($builder)
            ->responseDate($builder)
        ;
    }
}
