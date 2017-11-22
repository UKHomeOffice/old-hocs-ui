<?php

namespace HomeOffice\AlfrescoApiBundle\Repository;

use GuzzleHttp\Client as Guzzle;
use HomeOffice\AlfrescoApiBundle\Entity\Ticket;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

class TicketRepository
{
 
    const ALFRESCO_NOT_AVAILABLE_ERROR = 'Alfresco not available';
    // @codingStandardsIgnoreStart
    const COULD_NOT_COMMUNICATE_WITH_PROCESS_MANAGER_EXCEPTION_MESSAGE = 'Could not communicate with process manager: {exceptionMessage}';
    // @codingStandardsIgnoreEnd
 
    /**
     * @var \GuzzleHttp\Client
     */
    private $apiClient;
 
    /**
     *
     * @var LoggerInterface
     */
    private $logger;
 
    /**
     * @param Guzzle $apiClient
     */
    public function __construct(Guzzle $apiClient, LoggerInterface $logger)
    {
        $this->apiClient = $apiClient;
        $this->logger = $logger;
    }

    /**
     * @param $username
     * @param $password
     * @return Ticket
     */
    public function findOneByUsernameAndPassword($username, $password)
    {
        $body = json_encode(array('username' => $username, 'password' => $password));
        try {
            $response = $this->apiClient->post('s/api/login', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => $body
            ]);
        } catch (RequestException $exception) {
            if ($exception->getResponse() == null) {
                $this->logger->error(
                    self::COULD_NOT_COMMUNICATE_WITH_PROCESS_MANAGER_EXCEPTION_MESSAGE,
                    array('exceptionMessage' => $exception->getMessage())
                );
                return array('-1', self::ALFRESCO_NOT_AVAILABLE_ERROR);
            }
            $statusCode = $exception->getResponse()->getStatusCode();
            $response = json_decode($exception->getResponse()->getBody()->__toString());
            if ($statusCode == '500') {
                $this->logger->error(
                    self::COULD_NOT_COMMUNICATE_WITH_PROCESS_MANAGER_EXCEPTION_MESSAGE,
                    array('exceptionMessage' => $response->message)
                );
            }
            return array(
                $exception->getResponse()->getStatusCode(),
                isset($response->message) ? $response->message : '',
            );
        }

        $responseBody = json_decode($response->getBody()->__toString());

        if (!$response || !isset($responseBody->data->ticket)) {
            return $return = array($response->getStatusCode(), $response->message);
        }

        $ticket = new Ticket();
        $ticket->setTicket($responseBody->data->ticket);

        return $ticket;
    }
 
    /**
     *
     * @param string $ticket
     * @return boolean
     */
    public function validateTicket($ticket)
    {
        try {
            $this->apiClient->get("s/api/login/ticket/$ticket", [
                'query' => ['alf_ticket' => $ticket]
            ]);
        } catch (RequestException $exception) {
            return false;
        }
        return true;
    }
}
