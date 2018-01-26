<?php

namespace HomeOffice\AlfrescoApiBundle\Service\Dashboard;

use HomeOffice\AlfrescoApiBundle\Consumer\Dashboard as Consumer;
use Tedivm\StashBundle\Service\CacheService;

/**
 * Class Dashboard
 *
 * @package HomeOffice\AlfrescoApiBundle\Service\Dashboard
 */
class Dashboard
{
    /**
     * @var Consumer
     */
    protected $consumer;

    protected $cacheService;

    protected $cacheTimeout;

    private $topicKey;

    /**
     * Constructor
     *
     * @param Consumer $consumer
     */
    public function __construct(Consumer $consumer, CacheService $cacheService, $cacheTimeout)
    {
        $this->consumer = $consumer;
        $this->cacheService = $cacheService;
        $this->cacheTimeout = $cacheTimeout;
        $this->topicKey = "symfonyDashboard";
    }


    public function getChart()
    {
        $data = $this->getChartFromCache($this->topicKey);
        return new DashboardChart($data);
    }

    private function getChartFromCache($key)
    {
        $listKey = $key;
        $cacheItem = $this->cacheService->getItem($listKey);
        $list = $cacheItem->get();
        if ($cacheItem->isMiss()) {
            $this->getChartFromAlfresco($listKey);
            $list = $cacheItem->get();
        }
        return $list;
    }

    /**
     *
     * @throws \Exception
     */
    private function getChartFromAlfresco($listName)
    {
        $data = $this->consumer->get();
        if ($data === false) {
            throw new \Exception('Could not retrieve data from Alfresco');
        }
        $this->storeListInCache($listName,$data);
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
