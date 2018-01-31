<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

/**
 * Class CaseCorrespondenceSubType
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
final class CaseCorrespondenceSubType extends ConstantHelper
{
    const MIN = 'Ministerial';
    const TRO = 'Treat Official';
    const DTEN = 'DCU No. 10';
    const FOI = 'FOI';
    const FTC = 'Internal Review: Time Complaint';
    const FTCI = 'ICO Time Complaint';
    const FSC = 'Internal Review: Substantive Complaint';
    const FSCI = 'ICO Substantive Complaint';
    const FLT = 'Lower Tribunal';
    const FUT = 'Upper Tribunal';
    const IMCB = 'UKVI B Ref';
    const IMCM = 'UKVI M Ref';
    const UTEN = 'UKVI No. 10';
    const COM = 'Archive';
    const COM1 = 'Stage 1';
    const COM2 = 'Stage 2';
    const DGEN = 'Director General';
    const GNR = 'General';
    const COL = 'HMPO Collective';
    const COR = 'HMPO Correspondence';

    /**
     * Returns an array of all the constants in the class.
     *
     * @return array
     */
    public static function getCorrespondenceSubTypeArray()
    {
        return self::getAll();
    }

    /**
     * Returns the case type for a given correspondence type
     *
     * @param string $correspondenceType
     *
     * @return string|null
     */
    public static function getCaseType($correspondenceType)
    {
        if (defined('self::' . $correspondenceType)) {
            $correspondenceType = constant('self::' . $correspondenceType);

            switch (true) {
                case in_array($correspondenceType, self::getDcuSubTypesArray()):
                    return 'DCU';
                case in_array($correspondenceType, self::getFoiSubTypesArray()):
                    return 'FOI';
                case in_array($correspondenceType, self::getUkviSubTypesArray()):
                    return 'UKVI';
                case in_array($correspondenceType, self::getHmpoSubTypesArray()):
                    return 'HMPO';
            }
        }

        return null;
    }

    /**
     * Returns an array of all the constants for the DCU subtype.
     *
     * @return array
     */
    public static function getDcuSubTypesArray()
    {
        return [
            'MIN'  => self::MIN,
            'TRO'  => self::TRO,
            'DTEN' => self::DTEN
        ];
    }

    /**
     * Returns an array of all the constants for the FOI subtype.
     *
     * @return array
     */
    public static function getFoiSubTypesArray()
    {
        return [
            'FOI'  => self::FOI,
            'FTC'  => self::FTC,
            'FSC'  => self::FSC,
            'FTCI' => self::FTCI,
            'FSCI' => self::FSCI
        ];
    }

    /**
     * Returns an array of all the constants for the PQ subtype.
     *
     * @return array
     */
    public static function getPqSubTypesArray()
    {
        return [
        ];
    }

    /**
     * Returns an array of all the constants for the UKVI subtype.
     *
     * @return array
     */
    public static function getUkviSubTypesArray()
    {
        return [
            'IMCB' => self::IMCB,
            'IMCM' => self::IMCM,
            'UTEN' => self::UTEN
        ];
    }

    /**
     * Returns an array of all the constants for the HMPO subtype.
     *
     * @return array
     */
    public static function getHmpoSubTypesArray()
    {
        return [
            'COM' => self::COM,
            'COM1' => self::COM1,
            'COM2' => self::COM2,
            'DGEN' => self::DGEN,
            'GNR'  => self::GNR,
            'COL'  => self::COL
        ];
    }

    /**
     * Get HMPO correspondence sub types
     *
     * @return array
     */
    public static function getHmpoCorrespondenceSubTypes()
    {
        return [
            'COM1' => self::COM1,
            'COM2' => self::COM2,
            'DGEN' => self::DGEN,
            'GNR'  => self::GNR
        ];
    }
}
