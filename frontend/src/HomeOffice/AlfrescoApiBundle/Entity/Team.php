<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

class Team
{
    /**
     * @var string
     */
    private $authorityName;

    /**
     *
     * @var string
     */
    private $displayName;

    /**
     * @var array
     */
    private $people;

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
     * @param array $people
     */
    public function setPeople($people)
    {
        $this->people = $people;

    }

    /**
     *
     * @return array
     */
    public function getPeople()
    {
        return $this->people;
    }
}
