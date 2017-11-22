<?php

namespace HomeOffice\AlfrescoApiBundle\Service\Topic;

/**
 * Class Topic
 *
 * @package HomeOffice\AlfrescoApiBundle\Service\Topic
 */
class Topic
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $unit;

    /**
     * @var string
     */
    protected $team;

    /**
     * Constructor
     *
     * @param string      $name
     * @param string      $unit
     * @param string|null $team
     */
    public function __construct($name, $unit, $team = null)
    {
        $this->name = $name;
        $this->unit = $unit;
        $this->team = $team;
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Get Team
     *
     * @return string
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * To String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
