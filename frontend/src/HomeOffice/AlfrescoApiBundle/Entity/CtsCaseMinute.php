<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class CtsCaseMinute
{

    /**
     * @var string
     */
    protected $minuteType;

    /**
     * @var date
     */
    protected $minuteDateTime;

    /**
     * @var string
     */
    protected $minuteUpdatedBy;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    protected $minuteContent;

    /**
     * @var string
     */
    protected $minuteQaReviewOutcomes;

    /**
     * @var string
     */
    protected $task;

    /**
     *
     * @return string
     */
    public function getMinuteType()
    {
        return $this->minuteType;
    }

    /**
     *
     * @return string
     */
    public function getMinuteDateTime()
    {
        return $this->minuteDateTime;
    }

    /**
     *
     * @return string
     */
    public function getMinuteUpdatedBy()
    {
        return $this->minuteUpdatedBy;
    }

    /**
     *
     * @param string $minuteType
     * @return object CtsCaseMinute
     */
    public function setMinuteType($minuteType)
    {
        $this->minuteType = $minuteType;
        return $this;
    }

    /**
     *
     * @param string $minuteDateTime
     * @return object CtsCaseMinute
     */
    public function setMinuteDateTime($minuteDateTime)
    {
        $this->minuteDateTime = $minuteDateTime;
        return $this;
    }

    /**
     *
     * @param string $minuteUpdatedBy
     * @return object CtsCaseMinute
     */
    public function setMinuteUpdatedBy($minuteUpdatedBy)
    {
        $this->minuteUpdatedBy = $minuteUpdatedBy;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getMinuteContent()
    {
        return $this->minuteContent;
    }

    /**
     * @param string $minuteContent
     *
     * @return CtsCaseMinute
     */
    public function setMinuteContent($minuteContent)
    {
        $this->minuteContent = $minuteContent;

        return $this;
    }

    /**
     * @param string $reviewOutcomes
     * @return CtsCaseMinute
     */
    public function setMinuteQaReviewOutcomes($reviewOutcomes)
    {
        $this->minuteQaReviewOutcomes = $reviewOutcomes;

        return $this;
    }

    /**
     * @return array
     */
    public function getMinuteQaReviewOutcomes()
    {
        return $this->minuteQaReviewOutcomes;
    }

    /**
     * @return string
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param  string $task
     * @return CtsCaseMinute
     */
    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }
}
