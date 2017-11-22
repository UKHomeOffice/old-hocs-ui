<?php

namespace HomeOffice\ProcessManagerAuthenticatorBundle\Security;

use HomeOffice\AlfrescoApiBundle\Repository\PersonRepository;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserProvider
 *
 * @package HomeOffice\ProcessManagerAuthenticatorBundle\Security
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var PersonRepository
     */
    private $personRepository;

    /**
     * Constructor
     *
     * @param PersonRepository $personRepository
     */
    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        $user = $this->personRepository->getUserDetails($username);
        if (!$user) {
            throw new UsernameNotFoundException("Username {$username} not found");
        }

        return $user;
    }

    /**
     * Refreshes the user for the account interface.
     *
     * To limit the number of requests to the Alfreso we do not refresh the user per request.
     * @see https://jira.digital.homeoffice.gov.uk/browse/BHERC-21
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    /**
     * Whether this provider supports the given user class
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return false !== in_array('HomeOffice\AlfrescoApiBundle\Entity\Person', class_implements($class));
    }
}
