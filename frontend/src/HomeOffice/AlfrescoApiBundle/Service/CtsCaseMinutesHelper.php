<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

/**
 * Class CtsCaseMinutesHelper
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
class CtsCaseMinutesHelper extends ConstantHelper
{
    const CASE_TASK_CTQ_APP = 'cqt approval';
    const CASE_TASK_PO_APP = 'private office approval';

    /**
     * @return array
     */
    public static function getCaseMinutesStatuses()
    {
        return self::getClassConstants(basename(__FILE__, '.php'));
    }
}
