<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

abstract class CtsNode
{
     
    /**
     * @var string
     */
    protected $id;
 
    /**
     * @var string
     */
    protected $workspace;
 
    /**
     * @var string
     */
    protected $store;
 
    /**
     *
     * @param string $workspace
     * @param string $store
     */
    public function __construct($workspace, $store)
    {
        $this->workspace = $workspace;
        $this->store = $store;
    }
 
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return string
     */
    public function getNodeId()
    {
        return str_replace($this->getWorkspace() . '://' . $this->getStore() . '/', '', $this->id);
    }

    /**
     * Set a boolean property from mixed value types
     *
     * @param mixed $value
     *
     * @return bool
     */
    protected function setBoolean($value)
    {
        return strtoupper($value) === "TRUE" || $value === 1 || $value === '1' || $value === true;
    }
}
