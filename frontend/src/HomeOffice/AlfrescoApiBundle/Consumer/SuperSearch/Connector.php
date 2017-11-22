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
class Connector extends AbstractConsumer
{
    /**
     * @var string
     */
     protected $url = '/alfresco/cmisbrowser/';

    /**
     * @var array
     */
     private $standardPostFields = [
         'searchAllVersions'       => false,
         'includeRelationships'    => 'none',
         'renditionFilter'         => 'cmis:none',
         'includeAllowableActions' => false,
         'cmisaction'              => 'query',
     ];

    /**
     * Connector constructor.
     *
     * @param Guzzle               $api
     * @param SessionTicketStorage $token
     * @param SuperSearch          $superSearch
     */
    public function __construct(Guzzle $api, SessionTicketStorage $token, SuperSearch $superSearch) {
        parent::__construct($api, $token);
        $this->mappers = [$superSearch];
    }

    /**
     * @param  $repositoryId
     * @param  $statement
     * @param  int $limit
     * @param  int $offset
     *
     * @return array
     */
    public function search($repositoryId, Statement $statement, $limit = 10, $offset = 0)
    {
        $response = $this->api->post($this->url . $repositoryId, [
            'config' => [ 'curl' => [ CURLOPT_USERPWD => ':' . $this->token->getAuthenticationTicket()->getTicket() ] ],
            'body'  => array_merge(
                $this->standardPostFields, [
                    'statement' => $statement->generate(),
                    'maxItems'  => $limit,
                    'skipCount' => $offset,
                ])
        ]);

        if (!$this->checkResponseStatus($response)) {
            return [
                'results'      => [],
                'hasMoreItems' => false,
                'numItems'     => 0,
            ];
        }

        return $this->map($this->convertResponse($response));
    }
}
