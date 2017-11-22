<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

/**
 * Class ConstantHelper
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
abstract class ConstantHelper
{
    /**
     * Get All
     *
     * @param bool $keysToValues
     *
     * @return array
     */
    public static function getAll($keysToValues = false)
    {
        $constants = (new \ReflectionClass(static::class))->getConstants();

        if ($keysToValues === true) {
            $constants = array_combine($constants, $constants);
        }

        return $constants;
    }

    /**
     * Returns an array of the constants within the class name passed in with values as keys
     * @param $className
     * @return array
     *
     * @dperecated Use getAll instead
     */
    public static function getClassConstants($className)
    {
        $ary = [];
        $refl = new \ReflectionClass("\\HomeOffice\\AlfrescoApiBundle\\Service\\$className");
        foreach ($refl->getConstants() as $const) {
            $ary[$const] = $const;
        }
        return $ary;
    }

    /**
     * @param  array $constants
     * @param  array $filter
     *
     * @return array
     */
    public static function filterConstants(array $constants, array $filter = [])
    {
        foreach ($filter as $value) {
            unset($constants[$value]);
        }

        return $constants;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        // Cannot instantiate this class
    }

    /**
     * Clone
     */
    private function __clone()
    {
        // Cannot instantiate this class
    }
}
