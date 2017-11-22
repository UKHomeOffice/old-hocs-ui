<?php

namespace HomeOffice\AlfrescoApiBundle\Service\Topic;

/**
 * Class TopicGroup
 *
 * @package HomeOffice\AlfrescoApiBundle\Service\Topic
 */
class TopicGroup
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $caseType;

    /**
     * @var Topic[]
     */
    protected $topics = [];

    /**
     * Constructor
     *
     * @param string $name
     * @param string $caseType
     */
    public function __construct($name, $caseType)
    {
        $this->name = $name;
        $this->caseType = $caseType;
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
     * Get CaseType
     *
     * @return string
     */
    public function getCaseType()
    {
        return $this->caseType;
    }

    /**
     * Get Topics
     *
     * @return Topic[]
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * Has Topics
     *
     * @return bool
     */
    public function hasTopics()
    {
        return !empty($this->topics);
    }

    /**
     * Set Topics
     *
     * @param Topic $topic
     *
     * @return $this
     */
    public function addTopic(Topic $topic)
    {
        $this->topics[] = $topic;

        return $this;
    }
}
