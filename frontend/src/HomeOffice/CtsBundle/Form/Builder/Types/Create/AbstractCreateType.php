<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Create;

use HomeOffice\AlfrescoApiBundle\Service\CaseStatus;
use HomeOffice\CtsBundle\Form\Builder\Types\BaseType;

/**
 * Class AbstractCreateType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Create
 */
abstract class AbstractCreateType extends BaseType
{
    /**
     * @inheritdoc
     */
    public function getStage()
    {
        return CaseStatus::NEW_CASE;
    }
}
