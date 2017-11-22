<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Signoff;

use HomeOffice\AlfrescoApiBundle\Service\CaseStatus;
use HomeOffice\CtsBundle\Form\Builder\Types\BaseType;

/**
 * Class AbstractSignoffType
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Signoff
 */
abstract class AbstractSignoffType extends BaseType
{
    /**
     * @inheritdoc
     */
    public function getStage()
    {
        return CaseStatus::OBTAIN_SIGNOFF;
    }
}
