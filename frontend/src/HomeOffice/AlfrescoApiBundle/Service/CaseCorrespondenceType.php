<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

/**
 * Class CaseCorrespondenceType
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
final class CaseCorrespondenceType extends ConstantHelper
{
    const DCU  = 'DCU';
    const FOI  = 'FOI';
    const UKVI = 'UKVI';
    const HMPO = 'HMPO';

    /**
     * Returns an array of all the constants in the class.
     *
     * @return array
     */
    public static function getCorrespondenceArray()
    {
        return self::getAll();
    }

    /**
     * @param $caseType
     * @return mixed
     */
    public static function getSubtypesFromCaseType($caseType)
    {
        return array_keys(self::getCorrespondenceArrayWithSubTypes()[$caseType]);
    }

    /**
     * Returns an array of all the correspondence types with their subtypes.
     *
     * @param string|null $filterType Filter by type
     *
     * @return array
     */
    public static function getCorrespondenceArrayWithSubTypes($filterType = null)
    {
        $correspondenceTypes = [
            CaseCorrespondenceType::DCU  => CaseCorrespondenceSubType::getDcuSubTypesArray(),
            CaseCorrespondenceType::FOI  => CaseCorrespondenceSubType::getFoiSubTypesArray(),
            CaseCorrespondenceType::UKVI => CaseCorrespondenceSubType::getUkviSubTypesArray(),
            CaseCorrespondenceType::HMPO => CaseCorrespondenceSubType::getHmpoSubTypesArray(),
        ];

        return $filterType ? $correspondenceTypes[$filterType] : $correspondenceTypes;
    }

    /**
     * @return array
     */
    public static function getCorrespondenceTypesForCaseInitiation()
    {
        $correspondenceTypes = [
            CaseCorrespondenceType::DCU => [
                'MIN'  => CaseCorrespondenceSubType::MIN,
                'TRO'  => CaseCorrespondenceSubType::TRO,
                'DTEN' => CaseCorrespondenceSubType::DTEN
            ],
            CaseCorrespondenceType::FOI => [
                'FOI'  => CaseCorrespondenceSubType::FOI,
                'FTC'  => CaseCorrespondenceSubType::FTC,
                'FSC'  => CaseCorrespondenceSubType::FSC,
                'FTCI' => CaseCorrespondenceSubType::FTCI,
                'FSCI' => CaseCorrespondenceSubType::FSCI
            ],
            CaseCorrespondenceType::UKVI => [
                'IMCB' => CaseCorrespondenceSubType::IMCB,
                'IMCM' => CaseCorrespondenceSubType::IMCM,
                'UTEN' => CaseCorrespondenceSubType::UTEN
            ],
            CaseCorrespondenceType::HMPO => [
                'COR' => CaseCorrespondenceSubType::COR,
                'COL' => CaseCorrespondenceSubType::COL,
                'COM' => CaseCorrespondenceSubType::COM
            ],
        ];

        return $correspondenceTypes;
    }

    /**
     * Returns an array of all the correspondence types with their subtypes, for use in bulk create case.
     *
     * @return array
     */
    public static function getCorrespondenceArrayWithSubTypesForBulkCreateCase()
    {
        return [
            CaseCorrespondenceType::DCU => [
                'MIN'  => CaseCorrespondenceSubType::MIN,
                'TRO'  => CaseCorrespondenceSubType::TRO,
                'DTEN' => CaseCorrespondenceSubType::DTEN,
            ],
            CaseCorrespondenceType::FOI => [
                'FOI'  => CaseCorrespondenceSubType::FOI,
                'FTC'  => CaseCorrespondenceSubType::FTC,
                'FTCI' => CaseCorrespondenceSubType::FTCI,
                'FSC'  => CaseCorrespondenceSubType::FSC,
                'FSCI' => CaseCorrespondenceSubType::FSCI
            ],
            CaseCorrespondenceType::UKVI => [
                'IMCB' => CaseCorrespondenceSubType::IMCB,
                'IMCM' => CaseCorrespondenceSubType::IMCM,
                'UTEN' => CaseCorrespondenceSubType::UTEN,
            ],
            CaseCorrespondenceType::HMPO => [
//                'COM' => CaseCorrespondenceSubType::COM,
            ]
        ];
    }
}
