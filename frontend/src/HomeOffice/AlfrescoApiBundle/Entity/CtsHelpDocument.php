<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

class CtsHelpDocument extends CtsNode
{
 
    /**
     *
     * @var string
     */
    private $name;
 
    /**
     *
     * @param string $workspace
     * @param string $store
     */
    public function __construct($workspace, $store)
    {
        parent::__construct($workspace, $store);
    }
 
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
