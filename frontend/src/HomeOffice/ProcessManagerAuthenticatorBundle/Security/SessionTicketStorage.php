<?php

namespace HomeOffice\ProcessManagerAuthenticatorBundle\Security;

use HomeOffice\ProcessManagerInterfaceBundle\Entity\AuthenticationTicketAware;
use HomeOffice\AlfrescoApiBundle\Entity\Ticket;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionTicketStorage implements AuthenticationTicketAware
{

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var array
     */
    private $authenticationListeners = array();

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param Ticket $alfTicket
     */
    public function setAuthenticationTicket(Ticket $alfTicket)
    {
        $this->session->set(__CLASS__, $alfTicket);
    }

    /**
     * @return Ticket
     */
    public function getAuthenticationTicket()
    {
        return $this->session->get(__CLASS__);
    }
}
