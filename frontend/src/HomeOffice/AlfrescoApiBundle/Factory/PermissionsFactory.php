<?php

namespace HomeOffice\AlfrescoApiBundle\Factory;

use HomeOffice\AlfrescoApiBundle\Entity\CasesPermissions;

/**
 * Class PermissionsFactory
 * @package HomeOffice\AlfrescoApiBundle\Entity
 */
class PermissionsFactory
{

    /**
     * @param array $parameters
     * @return CasesPermissions
     */
    public function build($parameters, $type)
    {
        $permissions = new $type();

        foreach ($parameters as $key => $value) {
            $methodName = "set" . ucfirst($key);

            if (method_exists($permissions, $methodName)) {
                $permissions->$methodName($value);
            }
        }

        return $permissions;
    }
}
