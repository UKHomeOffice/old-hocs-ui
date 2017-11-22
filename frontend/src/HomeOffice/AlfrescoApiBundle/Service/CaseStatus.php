<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

/**
 * Class CaseStatus
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
final class CaseStatus extends ConstantHelper
{
    const NEW_CASE = 'New';
    const DRAFT = 'Draft';
    const APPROVALS = 'Approvals';
    const OBTAIN_SIGNOFF = 'Obtain sign-off';
    const DISPATCH = 'Dispatch';
    const OGD = 'OGD';
    const NFA = 'NFA';
    const HOLD = 'Hold';
    const COMPLETED = 'Completed';
    const DELETED = 'Deleted';

    /**
     * Returns an array of all the constants in the class, with values as keys.
     * @return array
     */
    public static function getStatusArray()
    {
        return self::getClassConstants(basename(__FILE__, '.php'));
    }

    /**
     * Returns an array of all the constants in the class, with names as keys.
     * @return array
     */
    public static function getStatusArrayConstants()
    {
        $refl = new \ReflectionClass('\HomeOffice\AlfrescoApiBundle\Service\CaseStatus');
        return $refl->getConstants();
    }

    /**
     * Returns an array of all the constants for filtering (excludes Completed and Deleted).
     * @return array
     */
    public static function getStatusForFilterArray()
    {
        $statusArray = array(self::NEW_CASE => self::NEW_CASE,
            self::DRAFT => self::DRAFT,
            self::APPROVALS => self::APPROVALS,
            self::OGD => self::OGD,
            self::NFA => self::NFA,
            self::HOLD => self::HOLD,
            self::OBTAIN_SIGNOFF => self::OBTAIN_SIGNOFF,
            self::DISPATCH => self::DISPATCH);
        asort($statusArray);
        return $statusArray;
    }
}
