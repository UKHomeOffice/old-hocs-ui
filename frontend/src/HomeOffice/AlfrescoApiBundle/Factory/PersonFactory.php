<?php

namespace HomeOffice\AlfrescoApiBundle\Factory;

use HomeOffice\AlfrescoApiBundle\Entity\Person;

/**
 * Class PersonFactory
 * @package HomeOffice\AlfrescoApiBundle\Entity
 */
class PersonFactory
{

    /**
     * @param array $parameters
     * @return Person
     */
    public function build($parameters)
    {
        $person = new Person();

        foreach ($parameters as $key => $value) {
            $methodName = "set" . ucfirst($key);

            if (method_exists($person, $methodName)) {
                $person->$methodName($value);
            }
        }

        return $person;
    }
}
