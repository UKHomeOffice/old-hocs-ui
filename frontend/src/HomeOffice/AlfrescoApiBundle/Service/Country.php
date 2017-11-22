<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

abstract class Country extends ConstantHelper
{

    const UK = 'United Kingdom';
    const OTHER = 'Other';

    /**
     * Returns an array of all the constants in the class.
     * @return array
     */
    public static function getCountriesArray()
    {
        return self::getClassConstants(basename(__FILE__, '.php'));
    }
}
