<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer;

/**
 * Class CtsCase
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer
 * @author  Adam Lewis <adam.lewis@homeoffice.digital.gov.uk>
 * @since   2016-05-13
 */
class CtsCase extends AbstractConsumer
{
    /**
     * @var string
     */
    protected $url = 's/homeoffice/ctsv2/case';

    protected $cacheService;

    protected $cacheTimeout;

    public function __construct(CacheService $cacheService, $cacheTimeout) {
        $this->cacheService = $cacheService;
        $this->cacheTimeout = $cacheTimeout;
    }

    /**
     * @param bool $nodeRef
     * @return bool|string
     */
    public function get($nodeRef = false)
    {
        $topicKey = "symfonyCTSCase" . $nodeRef;
        return $this->getTodoListFromCache($topicKey, $nodeRef);

    }

    private function getTodoListFromCache($listKey, $node)
    {
        $cacheItem = $this->cacheService->getItem($listKey);
        $list = $cacheItem->get();
        if ($cacheItem->isMiss()) {
            print "Cache MISS" . $listKey;
            $this->getFromAlfresco($listKey, $node);
            $list = $cacheItem->get();
        }
        return $list;
    }

    private function getFromAlfresco($listName, $nodeRef)
    {
        $this->setQueryField('nodeRef', $nodeRef);
        $data = parent::get();
        $this->storeListInCache($listName,$data);
    }

    private function storeListInCache($name, $list)
    {
        $cacheItem = $this->cacheService->getItem($name);
        $cacheItem->set($list, $this->cacheTimeout);
    }
}
