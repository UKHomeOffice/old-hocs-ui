<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BulkCaseEntry
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity
 */
class BulkCaseEntry
{
    /**
     * @var string $correspondenceType
     *
     * @Assert\NotBlank()
     */
    protected $correspondenceType;

    /**
     * @var UploadedFile[]
     */
    protected $files;

    /**
     * @var string
     */
    protected $assignedUnit;

    /**
     * @var string
     */
    protected $assignedTeam;

    /**
     * @var string
     */
    protected $assignedUser;


    /**
     * @return string
     */
    public function getCorrespondenceType()
    {
        return $this->correspondenceType;
    }

    /**
     * @param string $correspondenceType
     *
     * @return $this
     */
    public function setCorrespondenceType($correspondenceType)
    {
        $this->correspondenceType = $correspondenceType;

        return $this;
    }
    /**
     * @return UploadedFile[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param UploadedFile[] $files
     *
     * @return $this
     */
    public function setFiles(array $files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * @return string
     */
    public function getAssignedTeam()
    {
        return $this->assignedTeam;
    }

    /**
     * @param string $assignedTeam
     *
     * @return $this
     */
    public function setAssignedTeam($assignedTeam)
    {
        $this->assignedTeam = $assignedTeam;

        return $this;
    }

    /**
     * @return string
     */
    public function getAssignedUnit()
    {
        return $this->assignedUnit;
    }

    /**
     * @param string $assignedUnit
     *
     * @return $this
     */
    public function setAssignedUnit($assignedUnit)
    {
        $this->assignedUnit = $assignedUnit;

        return $this;
    }

    /**
     * @return string
     */
    public function getAssignedUser()
    {
        return $this->assignedUser;
    }

    /**
     * @param string $assignedUser
     *
     * @return $this
     */
    public function setAssignedUser($assignedUser)
    {
        $this->assignedUser = $assignedUser;

        return $this;
    }
}
