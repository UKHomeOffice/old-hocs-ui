<?php

namespace HomeOffice\AlfrescoApiBundle\Service\Topic;

use HomeOffice\AlfrescoApiBundle\Consumer\TopicConsumer;
use Tedivm\StashBundle\Service\CacheService;

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

    protected $cacheService;

    protected $cacheTimeout;

    private $topicKey;

    /**
     * Constructor
     *
     * @param TopicConsumer $consumer
     * @param CacheService $cacheService
     * @param $cacheTimeout
     */
    public function __construct(TopicConsumer $consumer, CacheService $cacheService, $cacheTimeout)
    {
        $this->consumer = $consumer;
        $this->cacheService = $cacheService;
        $this->cacheTimeout = $cacheTimeout;
        $this->topicKey = "symfonyTopics";

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
        return $this->getListFromCache($this->topicKey,$correspondenceType,$unit);
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

    private function getListFromCache($listName, $correspondenceType, $unit)
    {
        $listKey = $listName + $correspondenceType + $unit;
        $cacheItem = $this->cacheService->getItem($listKey);
        $list = $cacheItem->get();
        if ($cacheItem->isMiss()) {
            $this->getTopicList($listKey, $correspondenceType, $unit);
            $list = $cacheItem->get();
        }
        return $list;
    }

    private function getTopicList($listName, $correspondenceType = null, $unit = null)
    {
        $data = $this->consumer->get();
        if ($data === false) {
            $data = [];
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

        $this->storeListInCache($listName,$topics);
    }

    /**
     *
     * @param string $name
     * @param array $list
     */
    private function storeListInCache($name, $list)
    {
        $cacheItem = $this->cacheService->getItem($name);
        $cacheItem->set($list, $this->cacheTimeout);
    }
}
