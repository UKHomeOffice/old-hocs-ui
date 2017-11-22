<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

class Unit
{
    /**
     * @var string
     */
    private $authorityName;

    /**
     * @var string
     */
    private $displayName;

    /**
     * @var array
     */
    private $teams = [];

    /**
     * @return string
     */
    public function getAuthorityName()
    {

        return $this->authorityName;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return preg_replace('/[^a-z0-9]/', '-', strtolower(trim(strip_tags($this->getAuthorityName()))));
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;

    }

    /**
     * @return Team[]
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @return bool
     */
    public function hasTeams()
    {
        return count($this->teams) ? true : false;
    }

    /**
     * @param string $authorityName
     */
    public function setAuthorityName($authorityName)
    {
        $this->authorityName = $authorityName;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @param array $teams
     *
     * @return $this
     */
    public function setTeams(array $teams = [])
    {
        $this->teams = $teams;

        return $this;
    }

    /**
     * @param Team $team
     */
    public function addTeam(Team $team)
    {
        $this->teams[] = $team;
    }
}
