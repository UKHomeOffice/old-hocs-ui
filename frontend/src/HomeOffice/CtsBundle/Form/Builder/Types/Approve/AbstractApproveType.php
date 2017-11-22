<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Approve;

use HomeOffice\AlfrescoApiBundle\Service\CaseStatus;
use HomeOffice\CtsBundle\Form\Builder\Types\BaseType;

/**
 * Class AbstractApproveType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Approve
 */
abstract class AbstractApproveType extends BaseType
{
    /**
     * @inheritdoc
     */
    public function getStage()
    {
        return CaseStatus::APPROVALS;
    }
}
