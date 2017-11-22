<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

abstract class GlobalPermissions
{
    /**
     * @var boolean
     */
    private $canCreateFolder;
 
    /**
     * @return boolean
     */
    public function getCanCreateFolder()
    {
        return $this->canCreateFolder;
    }

    /**
     * @param boolean $canCreateFolder
     */
    public function setCanCreateFolder($canCreateFolder)
    {
        $this->canCreateFolder = $canCreateFolder;
    }
}
