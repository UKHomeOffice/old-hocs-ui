<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Dispatch;

use HomeOffice\AlfrescoApiBundle\Service\CaseStatus;
use HomeOffice\CtsBundle\Form\Builder\Types\BaseType;

/**
 * Class AbstractDispatchType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Dispatch
 */
abstract class AbstractDispatchType extends BaseType
{
    /**
     * @inheritdoc
     */
    public function getStage()
    {
        return CaseStatus::DISPATCH;
    }
}
