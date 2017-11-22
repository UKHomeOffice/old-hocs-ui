<?php
namespace HomeOffice\ProcessManagerAuthenticatorBundle\Security;

use GuzzleHttp\Exception\RequestException;
use HomeOffice\ProcessManagerInterfaceBundle\Entity\AuthenticationTicketAware;
use HomeOffice\ProcessManagerInterfaceBundle\Entity\AuthenticationTicketPublisher;
use HomeOffice\AlfrescoApiBundle\Entity\Ticket;
use HomeOffice\AlfrescoApiBundle\Repository\TicketRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class Authenticator implements SimpleFormAuthenticatorInterface, AuthenticationTicketPublisher
{
    private $ticketRepository;
    private $logger;
    private $authenticationTokenListeners = array();

    const INVALID_USERNAME_OR_PASSWORD = 'Incorrect username or password';
    const PASSWORD_EXPIRED = 'Password reset required';
    // @codingStandardsIgnoreStart
    const ALFRESCO_LOGIN_ERROR = 'We are currently experiencing some technical difficulties, please contact your system administrator.';
    // @codingStandardsIgnoreEnd
 
    public function __construct(TicketRepository $ticketRepository, LoggerInterface $logger)
    {
        $this->ticketRepository = $ticketRepository;
        $this->logger = $logger;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $ticket = $this->ticketRepository->findOneByUsernameAndPassword(
            $token->getUsername(),
            $token->getCredentials()
        );
     
        if ($ticket instanceof Ticket) {
            foreach ($this->authenticationTokenListeners as $authenticationListener) {
                $authenticationListener->setAuthenticationTicket($ticket);
            }
            $user = $userProvider->loadUserByUsername($token->getUsername());
            return new UsernamePasswordToken(
                $user,
                $token->getCredentials(),
                $providerKey,
                $user->getRoles()
            );
        }
     
        if ($ticket[0] == '403' && strpos($ticket[1], 'Password reset required')) {
            throw new AccountExpiredException(self::PASSWORD_EXPIRED);
        }
        if ($ticket[0] == '403' && strpos($ticket[1], 'Login failed')) {
            throw new AuthenticationException(self::INVALID_USERNAME_OR_PASSWORD);
        }
        if ($ticket[0] == '500' && strpos($ticket[1], 'User does not exist and could not be created')) {
            throw new AuthenticationException(self::INVALID_USERNAME_OR_PASSWORD);
        }
        // if at this point we don't have a Ticket, and it's not an error we know about
        // then throw a general alfresco login error and look in the logs for the issue
        throw new AuthenticationException(self::ALFRESCO_LOGIN_ERROR);
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }

    /**
     * @param AuthenticationTicketAware $listener
     * @return mixed
     */
    public function addAuthenticationTicketListener(AuthenticationTicketAware $listener)
    {
        $this->authenticationTokenListeners[] = $listener;
    }
}
