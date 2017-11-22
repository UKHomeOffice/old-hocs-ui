<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Draft;

use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FTCI
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Draft
 */
class FTCI extends BaseFoi
{
    use Groups\Allocate;

    /**
     * @inheritdoc
     */
    public function buildElements(FormBuilderInterface $builder, array $options)
    {
        parent::buildElements($builder, $options);

        $this->allocate($builder, $this->getListService());
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'FTCIDraft';
    }
}
