<?php

namespace HomeOffice\AlfrescoApiBundle\Factory;

/**
 * Class CtsNodeFactory
 * @package HomeOffice\AlfrescoApiBundle\Entity
 */
abstract class CtsNodeFactory
{
 
    /**
     * @var string
     */
    private $workspace;

    /**
     * @var string
     */
    private $store;

    /**
     * @param string $workspace
     * @param string $store
     */
    public function __construct($workspace, $store)
    {
        $this->workspace = $workspace;
        $this->store = $store;
    }
 
    /**
     *
     * @return string
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }

    /**
     *
     * @param string $workspace
     */
    public function setWorkspace($workspace)
    {
        $this->workspace = $workspace;
    }

    /**
     *
     * @return string
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     *
     * @param string $store
     */
    public function setStore($store)
    {
        $this->store = $store;
    }

    /**
     * @param array $parameters
     * @return Object
     */
    abstract public function build($parameters);
}
