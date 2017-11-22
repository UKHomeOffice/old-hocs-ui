<?php

namespace HomeOffice\AlfrescoApiBundle\Factory;

use HomeOffice\AlfrescoApiBundle\Entity\Team;

/**
 * Class TeamFactory
 * @package HomeOffice\AlfrescoApiBundle\Entity
 */
class TeamFactory
{

    /**
     * @param array $parameters
     * @return Team
     */
    public function build($parameters)
    {
        $team = new Team();

        foreach ($parameters as $key => $value) {
            $methodName = "set" . ucfirst($key);

            if (method_exists($team, $methodName)) {
                $team->$methodName($value);
            }
        }

        return $team;
    }
}
