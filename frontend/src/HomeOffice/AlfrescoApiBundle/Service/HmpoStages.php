<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

abstract class HmpoStages extends ConstantHelper
{
    const STAGE1 = 'Stage 1';
    const STAGE2 = 'Stage 2';
    const MP_COMPLAINT = 'MP complaint';
    const General = 'General';

    /**
     * Returns an array of all the constants in the class.
     * @return array
     */
    public static function getHmpoStagesArray()
    {
        return self::getClassConstants(basename(__FILE__, '.php'));
    }
}
