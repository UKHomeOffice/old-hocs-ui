<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

/**
 * Class PQLordsMinister
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
class PQLordsMinister extends ConstantHelper
{
    const LORDS_MINISTER_OPTION_ONE     = "Lord Bates";
    const LORDS_MINISTER_OPTION_TWO     = "Lord Ahmad";
    const LORDS_MINISTER_OPTION_THREE   = "Lord's Whip";
    const LORDS_MINISTER_OPTION_FOUR    = "Baroness Williams";
    const LORDS_MINISTER_OPTION_FIVE    = "Baroness Shields";

    /**
     * @return array
     */
    public static function getPQLordsMinisterArray()
    {
        return self::getClassConstants(basename(__FILE__, '.php'));
    }
}
