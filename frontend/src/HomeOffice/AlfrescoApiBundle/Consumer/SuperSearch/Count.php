<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch;

use HomeOffice\AlfrescoApiBundle\Consumer\AbstractConsumer;
use GuzzleHttp\Client as Guzzle;
use HomeOffice\AlfrescoApiBundle\Mapper\SuperSearch;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;

/**
 * Class Connector
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer\SuperSearch
 */
class Count extends AbstractConsumer
{
    /**
     * @var string
     */
     protected $url = '/alfresco/s/homeoffice/ctsv2/countquery?q=';

    /**
     * Connector constructor.
     *
     * @param Guzzle               $api
     * @param SessionTicketStorage $token
     */
    public function __construct(Guzzle $api, SessionTicketStorage $token) {
        parent::__construct($api, $token);
    }

    /**
     * @param Statement $statement
     * @return int
     */
    public function getCount(Statement $statement)
    {
        $response = $this->api->get($this->url . urlencode($statement->generate()), [
            'config' => [ 'curl' => [ CURLOPT_USERPWD => ':' . $this->token->getAuthenticationTicket()->getTicket() ] ],
        ]);
        if (!$this->checkResponseStatus($response)) {
            return 0;
        }

        return $this->convertResponse($response)['count'];
    }
}
