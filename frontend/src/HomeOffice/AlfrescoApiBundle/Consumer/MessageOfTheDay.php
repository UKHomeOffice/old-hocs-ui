<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer;

use GuzzleHttp\Client as Guzzle;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use Tedivm\StashBundle\Service\CacheService;
/**
 * Class MessageOfTheDay
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer
 * @author  Adam Lewis <adam.lewis@homeoffice.digital.gov.uk>
 * @since   2016-05-10
 */
final class MessageOfTheDay extends AbstractConsumer
{
    /**
     * @var string
     */
    protected $url = 's/cmis/p/Guest%20Home/message-of-the-day/content';

    /**
     * @var bool
     */
    protected $guest = true;


    protected $cacheService;

    protected $cacheTimeout;

    public function __construct(Guzzle $api, SessionTicketStorage $token,CacheService $cacheService, $cacheTimeout) {
        parent::__construct($api, $token);
        $this->cacheService = $cacheService;
        $this->cacheTimeout = $cacheTimeout;
    }

    /**
     * @param bool $nodeRef
     * @return bool|string
     */
    public function get(array $options = [], $listHandler = false)
    {
        $topicKey = "symfonyMOTD";
        return $this->getTodoListFromCache($topicKey);

    }

    private function getTodoListFromCache($listKey)
    {
        $cacheItem = $this->cacheService->getItem($listKey);
        $list = $cacheItem->get();
        if ($cacheItem->isMiss()) {
            $this->getFromAlfresco($listKey);
            $list = $cacheItem->get();
        }
        return $list;
    }

    private function getFromAlfresco($listName)
    {
        $data = parent::get();
        $this->storeListInCache($listName,$data);
    }

    private function storeListInCache($name, $list)
    {
        $cacheItem = $this->cacheService->getItem($name);
        $cacheItem->set($list, $this->cacheTimeout);
    }
}
