<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

abstract class FoiComplaint
{
    const HO = "HO";
    const REQUESTER = "Requester";
    const ICO = "ICO";
    const OTHER = "Other";
 
    const HO_WIN = "HO Win";
    const HO_WIN_IN_PART = "HO Win in part";
    const HO_LOSE = "HO Lose";
    const INFORMALLY_RESOLVED = "Informally resolved";
 
    /**
     * Returns an array of the constants required for the appellant field.
     * @return array
     */
    public static function getAppellantArray()
    {
        $appellantArray = array();
        $appellantArray[self::HO] = self::HO;
        $appellantArray[self::REQUESTER] = self::REQUESTER;
        $appellantArray[self::ICO] = self::ICO;
        $appellantArray[self::OTHER] = self::OTHER;
        return $appellantArray;
    }
 
    /**
     * Returns an array of the constants required for the outcome field.
     * @return array
     */
    public static function getOutcomeArray()
    {
        $appellantArray = array();
        $appellantArray[self::HO_WIN] = self::HO_WIN;
        $appellantArray[self::HO_WIN_IN_PART] = self::HO_WIN_IN_PART;
        $appellantArray[self::HO_LOSE] = self::HO_LOSE;
        $appellantArray[self::INFORMALLY_RESOLVED] = self::INFORMALLY_RESOLVED;
        return $appellantArray;
    }
}
