<?php

namespace HomeOffice\AlfrescoApiBundle\Factory;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;

/**
 * Class CtsCaseMinuteFactory
 * @package HomeOffice\AlfrescoApiBundle\Entity
 */
class CtsCaseMinuteFactory
{

    /**
     * @param array $parameters
     * @return CtsCaseMinute
     */
    public function build($parameters)
    {
        $ctsCaseMinute = new CtsCaseMinute();

        foreach ($parameters as $key => $value) {
            $methodName = "set" . ucfirst($key);

            if (method_exists($ctsCaseMinute, $methodName)) {
                $ctsCaseMinute->$methodName($value);
            }
        }

        return $ctsCaseMinute;
    }
}
