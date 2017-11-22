<?php

namespace HomeOffice\AlfrescoApiBundle\Factory;

use HomeOffice\AlfrescoApiBundle\Entity\Unit;

/**
 * Class UnitFactory
 * @package HomeOffice\AlfrescoApiBundle\Entity
 */
class UnitFactory
{

    /**
     * @param array $parameters
     * @return Unit
     */
    public function build($parameters)
    {
        $unit = new Unit();

        foreach ($parameters as $key => $value) {
            $methodName = "set" . ucfirst($key);

            if (method_exists($unit, $methodName)) {
                $unit->$methodName($value);
            }
        }

        return $unit;
    }
}
