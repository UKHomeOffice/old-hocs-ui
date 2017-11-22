<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer;

use HomeOffice\AlfrescoApiBundle\Entity\Person;
use HomeOffice\AlfrescoApiBundle\Entity\Team;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;

/**
 * Class EmailPreferences
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer
 */
class EmailPreferences extends AbstractConsumer
{
    /**
     * @var Person
     */
    protected $user;

    /**
     * @var string
     */
    protected $url = 's/homeoffice/ctsv2/user/groupEmailsOptOut';

    /**
     * @param array $groups
     */
    public function updatePreferences(array $groups)
    {
        $emailPreferences = [];

        foreach ($groups as $unitSlug => $group) {
            if (array_key_exists('unit', $group) && $group['unit'] == false) {
                $emailPreferences[] = $this->getUserUnitBySlug($unitSlug)->getAuthorityName();
            }
            if (array_key_exists('teams', $group)) {
                foreach ($group['teams'] as $teamSlug => $state) {
                    if ($state === false) {
                        $emailPreferences[] = $this->getUserTeamBySlug($teamSlug)->getAuthorityName();
                    }
                }
            }
        }

        $this->post(json_encode(['groups' => $emailPreferences]));
    }

    /**
     * Set User
     *
     * @param Person $user
     *
     * @return $this
     */
    public function setUser(Person $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param $slug
     *
     * @return Unit
     */
    private function getUserUnitBySlug($slug)
    {
        foreach ($this->user->getUnits() as $unit) {
            if ($unit->getSlug() === $slug) {
                return $unit;
            }
        }
    }

    /**
     * @param $slug
     *
     * @return Team
     */
    private function getUserTeamBySlug($slug)
    {
        foreach ($this->user->getTeams() as $team) {
            if ($team->getSlug() === $slug) {
                return $team;
            }
        }
    }
}
