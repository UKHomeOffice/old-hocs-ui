<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Draft;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FLT
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Draft
 */
class FLT extends BaseFoi
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'FLTDraft';
    }
}
