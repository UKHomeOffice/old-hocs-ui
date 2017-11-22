<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

abstract class Title extends ConstantHelper
{

    const DR = 'Dr';
    const MASTER = 'Master';
    const MISS = 'Miss';
    const MR = 'Mr';
    const MRS = 'Mrs';
    const MS = 'Ms';
    const PROF = 'Prof';
    const REVEREND = 'Rev';
    const SIR = 'Sir';
    const COUNCILLOR = 'Cllr';

    /**
     * Returns an array of all the constants in the class.
     * @return array
     */
    public static function getTitlesArray()
    {
        return self::getClassConstants(basename(__FILE__, '.php'));
    }
}
