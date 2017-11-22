<?php
namespace HomeOffice\ProcessManagerAuthenticatorBundle\Handler;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContextInterface;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use HomeOffice\AlfrescoApiBundle\Repository\TicketRepository;
use HomeOffice\AlfrescoApiBundle\Repository\PersonRepository;
use HomeOffice\AlfrescoApiBundle\Entity\Ticket;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;

class TicketAuthenticationHandler
{
    protected $securityContext;
    protected $router;
    protected $tokenStorage;
    protected $ticketRepository;
    protected $personRepository;
    protected $ctsHelper;

    /**
     *
     * @param SecurityContextInterface $securityContext
     * @param RouterInterface $router
     * @param SessionTicketStorage $tokenStorage
     * @param TicketRepository $ticketRepository
     */
    public function __construct(
        SecurityContextInterface $securityContext,
        RouterInterface $router,
        SessionTicketStorage $tokenStorage,
        TicketRepository $ticketRepository,
        PersonRepository $personRepository,
        CTSHelper $ctsHelper
    ) {
        $this->securityContext = $securityContext;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
        $this->ticketRepository = $ticketRepository;
        $this->personRepository = $personRepository;
        $this->ctsHelper = $ctsHelper;
    }
 
    /**
     *
     * @param GetResponseEvent $event
     * @return void
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }
        if ($this->tokenStorage->getAuthenticationTicket() == null) {
            return;
        }
        $ticket = $this->tokenStorage->getAuthenticationTicket()->getTicket();
        if ($ticket != null && $ticket != 'INVALID') {
            if (!$this->ticketRepository->validateTicket($ticket)) {
                $this->securityContext->setToken(null);
                $this->tokenStorage->setAuthenticationTicket(new Ticket('INVALID'));
                $event->setResponse(new RedirectResponse($this->router->generate('login')));
            }
            $resetPasswordRoute = $this->router->generate('reset_password') ;
            $resetPasswordFlag = $event->getRequest()->getRequestUri() == $resetPasswordRoute ? true : false;
            $user = $this->ctsHelper->getLoggedInUser();
            if ($user != null) {
                $passwordExpiryDate = $user->getPasswordExpiryDate();
            }
            $diff = 0;
            if ($user != null && $passwordExpiryDate != null && !$resetPasswordFlag) {
                $passwordExpiryDate = \DateTime::createFromFormat(
                    \DateTime::ISO8601,
                    $passwordExpiryDate
                );
                $now = new \DateTime();
                $diff = $passwordExpiryDate->format('YmdHis') - $now->format('YmdHis');
                if ($diff < 0) {
                    $event->setResponse(new RedirectResponse($resetPasswordRoute));
                }
            }
        }
    }
}
