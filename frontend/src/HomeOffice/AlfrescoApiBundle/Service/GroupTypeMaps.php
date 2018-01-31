<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

abstract class GroupTypeMaps extends ConstantHelper
{
    const DCU = 'GROUP_DCU';
    const FOI = 'GROUP_FOI';
    const PQ = 'GROUP_Parliamentary Questions';
    const UKVI = 'GROUP_UKVI';
    const HMPO_CCC = 'GROUP_HMPO CCC';
    const HMPO_PCU  = 'GROUP_HMPO PCU';
 
    /**
     * Returns an array of all the constants in the class.
     * @return array
     */
    public static function getGroupsArray()
    {
        return self::getClassConstants(basename(__FILE__, '.php'));
    }
 
    public static function getGroupCaseTypes($group)
    {
        $typesAndStages = [];
        switch ($group) {
            case self::DCU:
                $typesAndStages["TYPES"] = ['MIN', 'TRO', 'DTEN'];
                break;
            case self::FOI:
                $typesAndStages["TYPES"] = ['FOI', 'FTC', 'FTCI', 'FSC', 'FSCI', 'FLT', 'FUT'];
                break;
            case self::UKVI:
                $typesAndStages["TYPES"] = ['IMCM', 'IMCB', 'UTEN'];
                break;
            case self::HMPO_CCC:
                $typesAndStages["TYPES"] = ['GEN', 'COM', 'COL'];
                $typesAndStages["STAGES"] = [HmpoStages::STAGE1, HmpoStages::STAGE2];
                break;
            case self::HMPO_PCU:
                $typesAndStages["TYPES"] = ['COM', 'COL'];
                $typesAndStages["STAGES"] = [HmpoStages::MP_COMPLAINT];
                break;
        }
        return $typesAndStages;
    }
}
