<?php

namespace HomeOffice\AlfrescoApiBundle\Service\Topic;

use HomeOffice\AlfrescoApiBundle\Consumer\TopicConsumer;

/**
 * Class TopicService
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
class TopicService
{
    /**
     * @var TopicConsumer
     */
    protected $consumer;

    /**
     * Constructor
     *
     * @param TopicConsumer $consumer
     */
    public function __construct(TopicConsumer $consumer)
    {
        $this->consumer = $consumer;
    }

    /**
     * Get Topics
     *
     * @param string|null $correspondenceType
     * @param string|null $unit
     *
     * @return TopicGroup[]
     */
    public function getTopics($correspondenceType = null, $unit = null)
    {
        $data = $this->consumer->get();
        if ($data === false) {
            return [];
        }

        $topics = [];
        foreach ($data as $group) {
            if (is_null($correspondenceType) || $correspondenceType == $group['caseType']) {
                $topic = new TopicGroup($group['name'], $group['caseType']);
                foreach ($group['topicListItems'] as $item) {
                    if (is_null($unit) || $unit == $item['topicUnit']) {
                        $topic->addTopic(new Topic(
                            $item['topicName'],
                            $item['topicUnit'],
                            isset($item['topicTeam']) ? $item['topicTeam'] : null
                        ));
                    }
                }
                $topics[] = $topic;
            }
        }

        return $topics;
    }

    /**
     * Get Topics For Form
     * @param string|null $correspondenceType
     * @param string|null $unit
     *
     * @return array
     */
    public function getTopicsForForm($correspondenceType = null, $unit = null)
    {
        $topics = [];
        foreach ($this->getTopics($correspondenceType, $unit) as $group) {
            if ($group->hasTopics()) {
                foreach ($group->getTopics() as $topic) {
                    $topics[$group->getName()][$topic->getName()] = $topic->getName();
                }
            }
        }

        return $topics;
    }

    /**
     * Get Topic Names
     *
     * @param string|null $caseType
     * @param string|null $unit
     *
     * @return array
     */
    public function getTopicNames($caseType = null, $unit = null)
    {
        $topics = [];
        foreach ($this->getTopics($caseType, $unit) as $group) {
            if ($group->hasTopics()) {
                foreach ($group->getTopics() as $topic) {
                    $topics[] = $topic->getName();
                }
            }
        }

        return $topics;
    }
}
