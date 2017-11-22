<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

/*
 * Enum of PQ house types
 */

abstract class PQHouse extends ConstantHelper
{
    const HOUSE_OF_COMMONS = 'House of commons';
    const HOUSE_OF_LORDS = 'House of lords';

    /**
     * Returns an array of all the constants in the class.
     * @return array
     */
    public static function getPQHouseArray()
    {
        return self::getClassConstants(basename(__FILE__, '.php'));
    }
}
