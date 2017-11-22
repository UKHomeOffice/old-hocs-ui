<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Draft;

use HomeOffice\AlfrescoApiBundle\Service\CaseStatus;
use HomeOffice\CtsBundle\Form\Builder\Types\BaseType;

/**
 * Class AbstractDraftType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Draft
 */
abstract class AbstractDraftType extends BaseType
{
    /**
     * @inheritdoc
     */
    public function getStage()
    {
        return CaseStatus::DRAFT;
    }
}
