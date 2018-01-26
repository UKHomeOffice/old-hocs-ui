<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer;

use GuzzleHttp\Client as Guzzle;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use HomeOffice\AlfrescoApiBundle\Mapper\MarkupUnit;
use Tedivm\StashBundle\Service\CacheService;

/**
 * Class Todo List
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer
 * @author  Adam Lewis <adam.lewis@homeoffice.digital.gov.uk>
 * @since   2016-08-05
 */
class TodoList extends AbstractConsumer
{
    /**
     * @var string
     */
    protected $url = 'service/api/v2/homeoffice/cts/todolist';

    protected $cacheService;

    protected $cacheTimeout;

    /**
     * TodoList constructor.
     *
     * @param Guzzle $api
     * @param SessionTicketStorage $token
     * @param MarkupUnit $markupUnit
     */
    public function __construct(Guzzle $api, SessionTicketStorage $token, MarkupUnit $markupUnit, CacheService $cacheService, $cacheTimeout) {
        parent::__construct($api, $token);
        $this->mappers = [$markupUnit];
        $this->cacheService = $cacheService;
        $this->cacheTimeout = $cacheTimeout;
    }

    /**
     * @param  array $options
     * @param  bool $listHandler
     * @return array|bool
     */
    public function get(array $options = [], $listHandler = false)
    {
        $topicKey = "symfonyTodoList" . implode(".",$options);
        return $this->getTodoListFromCache($topicKey, $options, $listHandler);
    }

    private function getTodoListFromCache($listKey, $options, $listHandler)
    {
        $cacheItem = $this->cacheService->getItem($listKey);
        $list = $cacheItem->get();
        if ($cacheItem->isMiss()) {
            $this->getFromAlfresco($listKey, $options, $listHandler);
            $list = $cacheItem->get();
        }
        return $list;
    }

    private function getFromAlfresco($listName, array $options = [], $listHandler = false)
    {
        // A little hack whilst the backend get's altered to accept (null/false/'')
        $options = array_filter($options, function($value) {
            return $value !== null && $value !== false && $value !== '';
        });

        $this->setQueryField('includeAllowableActions', false);

        $data = parent::get($options, $listHandler);
        $this->storeListInCache($listName,$data);
    }

    private function storeListInCache($name, $list)
    {
        $cacheItem = $this->cacheService->getItem($name);
        $cacheItem->set($list, $this->cacheTimeout);
    }
}
