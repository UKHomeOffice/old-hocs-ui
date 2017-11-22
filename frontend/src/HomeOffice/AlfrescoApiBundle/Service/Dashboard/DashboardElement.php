<?php
namespace HomeOffice\AlfrescoApiBundle\Service\Dashboard;

/**
 * Class DashboardElement
 *
 * @package HomeOffice\AlfrescoApiBundle\Service\Dashboard
 */
class DashboardElement
{
    /**
     * @var string
     */
    private $caseType;

    /**
     * @var string
     */
    private $correspondenceType;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $task;

    /**
     * @var int
     */
    private $open;

    /**
     * @var int
     */
    private $openAndOverdue;

    /**
     * @var int
     */
    private $returned;

    /**
     * Constructor
     * @param string $caseType
     * @param string $correspondenceType
     * @param string $status
     * @param string $task
     */
    public function __construct($caseType, $correspondenceType, $status, $task)
    {
        $this->caseType = $caseType;
        $this->correspondenceType = $correspondenceType;
        $this->status = $status;
        $this->task = $task;
    }


    /**
     * Get CaseType
     *
     * @return mixed
     */
    public function getCaseType()
    {
        return $this->caseType;
    }

    /**
     * Get CorrespondenceType
     *
     * @return mixed
     */
    public function getCorrespondenceType()
    {
        return $this->correspondenceType;
    }

    /**
     * Get Status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get Task
     *
     * @return string
     */
    public function getTask()
    {
        return stripslashes($this->task);
    }

    /**
     * Get Open
     *
     * @return int
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * Increment Open
     *
     * @return $this
     */
    public function incrementOpen()
    {
        $this->open++;

        return $this;
    }


    /**
     * Set Open
     *
     * @param int $open
     *
     * @return $this
     */
    public function setOpen($open)
    {
        $this->open = $open;

        return $this;
    }

    /**
     * Get OpenAndOverdue
     *
     * @return int
     */
    public function getOpenAndOverdue()
    {
        return $this->openAndOverdue;
    }

    /**
     * Increment OpenAndOverdue
     *
     * @return $this
     */
    public function incrementOpenAndOverdue()
    {
        $this->openAndOverdue++;

        return $this;
    }

    /**
     * Set OpenAndOverdue
     *
     * @param int $openAndOverdue
     *
     * @return $this
     */
    public function setOpenAndOverdue($openAndOverdue)
    {
        $this->openAndOverdue = $openAndOverdue;

        return $this;
    }

    /**
     * Get Returned
     *
     * @return int
     */
    public function getReturned()
    {
        return $this->returned;
    }

    /**
     * Increment Returned
     *
     * @return $this
     */
    public function incrementReturned()
    {
        $this->returned++;

        return $this;
    }

    /**
     * Set Returned
     *
     * @param int $returned
     *
     * @return $this
     */
    public function setReturned($returned)
    {
        $this->returned = $returned;

        return $this;
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return ($this->getOpen() * 1000) + ($this->getOpenAndOverdue() * 100) + ($this->getReturned() *10);
    }
}
