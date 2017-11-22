<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer;

use GuzzleHttp\Client as Guzzle;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use HomeOffice\AlfrescoApiBundle\Mapper\MarkupUnit;

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

    /**
     * TodoList constructor.
     *
     * @param Guzzle $api
     * @param SessionTicketStorage $token
     * @param MarkupUnit $markupUnit
     */
    public function __construct(Guzzle $api, SessionTicketStorage $token, MarkupUnit $markupUnit) {
        parent::__construct($api, $token);
        $this->mappers = [$markupUnit];
    }

    /**
     * @param  array $options
     * @param  bool $listHandler
     * @return array|bool
     */
    public function get(array $options = [], $listHandler = false)
    {
        // A little hack whilst the backend get's altered to accept (null/false/'')
        $options = array_filter($options, function($value) {
            return $value !== null && $value !== false && $value !== '';
        });

        $this->setQueryField('includeAllowableActions', false);

        return parent::get($options, $listHandler);
    }
}
