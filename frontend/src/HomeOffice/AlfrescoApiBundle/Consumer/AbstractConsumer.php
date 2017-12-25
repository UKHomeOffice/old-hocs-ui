<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer;

use GuzzleHttp\Message\ResponseInterface as GuzzleResponse;
use GuzzleHttp\Client as Guzzle;
use HomeOffice\ListBundle\Service\ListHandler;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;

/**
 * Class AbstractConsumer
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer
 */
abstract class AbstractConsumer
{
    /**
     * @var Guzzle
     */
    protected $api;

    /**
     * @var SessionTicketStorage
     */
    protected $token;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var bool
     */
    protected $guest;

    /**
     * @var array
     */
    protected $mappers = [];

    /**
     * @var array $query An array to put the query options in
     */
    protected $query = [];

    /**
     * @var array $post An array to put the post options in
     */
    protected $post = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * AbstractConsumer constructor.
     *
     * @param Guzzle               $api
     * @param SessionTicketStorage $token
     */
    public function __construct(Guzzle $api, SessionTicketStorage $token)
    {
        $this->api = $api;
        $this->token = $token;

        $this->api->setDefaultOption('version', ["CURLOPT_HTTP_VERSION" => "CURL_HTTP_VERSION_1_0"]);
        $this->api->setDefaultOption('exceptions', false);
        $this->api->setDefaultOption('verify', true);
        $this->setStandardQueryFields();
    }

    /**
     * Add Option
     *
     * @param string $name
     * @param array  $array
     */
    public function addOption($name, $array)
    {
        $this->options[$name] = $array;
    }

    /**
     * Get
     *
     * Sets the response, then checks it and returns the body.
     *
     * Going forward, this method will be changed to accept a $key argument with a false default.  This way you could
     * retrieve one of something, like a case.
     *
     * @param array $options
     * @param bool  $listHandler
     *
     * @return bool|mixed
     */
    public function get(array $options = [], $listHandler = false)
    {
        $this->queryFields($options);

        empty($this->query) ? null : $this->addOption('query', $this->query);

        $response = $this->api->get($this->url, $this->options);

        if (!$this->checkResponseStatus($response)) {
            return false;
        }

        return $this->map($this->convertResponse($response), $listHandler);
    }

    /**
     * Post
     *
     * Sets the response, then checks it and returns the body.
     *
     * Going forward, this method will be changed to accept a $key argument with a false default.  This way you could
     * retrieve one of something, like a case.
     *
     * @param string           $body
     * @param array            $options
     * @param ListHandler|null $listHandler
     *
     * @return bool|mixed
     */
    public function post($body, array $options = [], ListHandler $listHandler = null)
    {

        $response = $this->api->post($this->url, [
            'body'  => $body,
            'query' => $this->query,
        ]);

        if (!$this->checkResponseStatus($response)) {
            return false;
        }

        return $this->map($this->convertResponse($response), $listHandler);
    }

    /**
     * Is Json
     *
     * @param $string
     * @return bool
     */
    protected function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Map
     *
     * @param array $response
     * @param bool  $listHandler
     *
     * @return mixed
     */
    protected function map($response, $listHandler = false)
    {
        if (empty($this->mappers) === false && is_array($response) === true) {
            foreach ($this->mappers as $mapper) {
                $response = $mapper->map($response, $listHandler);
            }
        }

        return $response;
    }

    /**
     * Convert Response
     *
     * @param GuzzleResponse $response
     *
     * @return array|string
     */
    protected function convertResponse(GuzzleResponse $response)
    {
        $body = $this->getResponseBody($response);

        return $this->isJson($body) ? json_decode($body, true) : $body;
    }

    /**
     * Options
     *
     * @param array $options
     */
    protected function queryFields(array $options)
    {
        if (is_array($options) && !empty($options)) {
            foreach ($options as $key => $value) {
                $this->setQueryField($key, $value);
            }
        }
    }

    /**
     * Post Options
     *
     * @param array $options
     */
    protected function postFields(array $options)
    {
        if (is_array($options) && !empty($options)) {
            foreach ($options as $key => $value) {
                $this->setPostField($key, $value);
            }
        }
    }

    /**
     * Set Query Option
     *
     * @param string $key
     * @param bool   $value
     */
    public function setQueryField($key, $value = false)
    {
        $this->query[$key] = $value;
    }

    /**
     * Set Post Option
     *
     * @param string $key
     * @param bool   $value
     */
    public function setPostField($key, $value = false)
    {
        $this->post[$key] = $value;
    }

    /**
     * Check Response Status
     *
     * @param GuzzleResponse $response
     *
     * @return bool
     */
    protected function checkResponseStatus(GuzzleResponse $response)
    {
        return $response->getStatusCode() === 200;
    }

    /**
     * Set Standard Query Options
     *
     * @return void
     */
    private function setStandardQueryFields()
    {
        $this->guest
            ? $this->setQueryField('guest', 'true')
            : $this->setQueryField('alf_ticket', $this->token->getAuthenticationTicket());
    }

    /**
     * Get Response Body
     *
     * @param GuzzleResponse $response
     *
     * @return string
     */
    private function getResponseBody(GuzzleResponse $response)
    {
        return $response->getBody()->__toString();
    }
}
