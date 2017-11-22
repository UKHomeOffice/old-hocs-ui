<?php

namespace HomeOffice\AlfrescoApiBundle\Entity;

use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Person
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity
 */
class Person implements UserInterface
{
    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $email;
 
    /**
     * @var string
     */
    private $passwordExpiryDate;
 
    /**
     * @var Unit[]
     */
    private $units = [];
 
    /**
     *
     * @var Team[]
     */
    private $teams = [];
 
    /**
     * @var bool
     */
    private $isManager = false;
 
    /**
     * @var CasesPermissions
     */
    private $casesPermissions;
 
    /**
     * @var DocumentTemplatesPermissions
     */
    private $documentTemplatesPermissions;
 
    /**
     * @var StandardLinesPermissions
     */
    private $standardLinesPermissions;
 
    /**
     * @var AutoCreatePermissions
     */
    private $autoCreatePermissions;

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }
 
    /**
     * Returns the users full name, comprised of first and last name.
     * @return string
     */
    public function getFullName()
    {
        if (empty($this->firstName) && empty($this->lastName)) {
            return '';
        }
        if (empty($this->firstName) && !empty($this->lastName)) {
            return $this->lastName;
        }
        if (!empty($this->firstName) && empty($this->lastName)) {
            return $this->firstName;
        }
        return $this->firstName." ".$this->lastName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }
 
    /**
     *
     * @return string
     */
    public function getPasswordExpiryDate()
    {
        return $this->passwordExpiryDate;
    }

    /**
     *
     * @param string $passwordExpiryDate
     */
    public function setPasswordExpiryDate($passwordExpiryDate)
    {
        $this->passwordExpiryDate = $passwordExpiryDate;
    }
     
    /**
     * @return Unit[]
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @param Unit $unit
     * @param bool $includeTeams
     *
     * @return bool
     */
    public function isMemberOfUnit(Unit $unit, $includeTeams = false)
    {
        foreach ($this->getUnits() as $userUnit) {
            if ($unit->getAuthorityName() === $userUnit->getAuthorityName()) {
                return true;
            }
        }

        if ($includeTeams === true) {
            foreach ($unit->getTeams() as $team) {
                if ($this->isMemberOfTeam($team) == true) {
                    return true;
                }
            }
        }

        return false;
    }
  
    /**
     * @return Unit|null
     */
    public function getFirstUnit()
    {
        if ($this->hasUnits()) {
            return $this->units[0];
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasUnits()
    {
        return count($this->units) ? true : false;
    }

    /**
     * @return Team[]
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @return Team[]
     *
     * @deprecated
     */
    public function getTeamsDeprecated()
    {
        $teams = [];
        foreach ($this->getUnits() as $unit) {
            $teams = array_merge($teams, $unit->getTeams());
        }

        return $teams;
    }

    /**
     * @param Team $team
     *
     * @return bool
     */
    public function isMemberOfTeam(Team $team)
    {
        return in_array($team, $this->getTeams());
    }
 
    /**
     * @return Team|null
     */
    public function getFirstTeam()
    {
        if ($this->hasTeams()) {
            return $this->teams[0];
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasTeams()
    {
        return count($this->teams) ? true : false;
    }

    /**
     * @param Unit[] $units
     */
    public function setUnits($units)
    {
        $this->units = $units;
    }

    /**
     * @param Team[] $teams
     *
     */
    public function setTeams(array $teams = [])
    {
        $this->teams = [];

        foreach ($teams as $team) {
            if (!in_array($team->getAuthorityName(), ['GROUP_Units', 'GROUP_Manager', 'GROUP_ALFRESCO_ADMINISTRATORS'])) {
                $this->teams[] = $team;
            }
        }
    }

    /**
     * @return bool
     */
    public function isManager()
    {
        return $this->isManager;
    }
 
    /**
     * @param string $isManager
     */
    public function setManager($isManager)
    {
        $this->isManager = $isManager === 'true';
    }
 
    /**
     * @return CasesPermissions
     */
    public function getCasesPermissions()
    {
        return $this->casesPermissions;
    }

    /**
     * @param CasesPermissions $casesPermissions
     */
    public function setCasesPermissions($casesPermissions)
    {
        $this->casesPermissions = $casesPermissions;
    }
 
    /**
     * @return DocumentTemplatesPermissions
     */
    public function getDocumentTemplatesPermissions()
    {
        return $this->documentTemplatesPermissions;
    }

    /**
     * @param DocumentTemplatesPermissions $documentTemplatesPermissions
     */
    public function setDocumentTemplatesPermissions($documentTemplatesPermissions)
    {
        $this->documentTemplatesPermissions = $documentTemplatesPermissions;
    }
 
    /**
     *
     * @return StandardLinesPermissions
     */
    public function getStandardLinesPermissions()
    {
        return $this->standardLinesPermissions;
    }
 
    /**
     *
     * @param StandardLinesPermissions $standardLinesPermissions
     */
    public function setStandardLinesPermissions($standardLinesPermissions)
    {
        $this->standardLinesPermissions = $standardLinesPermissions;
    }
 
    /**
     *
     * @return AutoCreatePermissions
     */
    public function getAutoCreatePermissions()
    {
        return $this->autoCreatePermissions;
    }
 
    /**
     *
     * @param AutoCreatePermissions $autoCreatePermissions
     */
    public function setAutoCreatePermissions($autoCreatePermissions)
    {
        $this->autoCreatePermissions = $autoCreatePermissions;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     *
     * @return string The password
     */
    public function getPassword()
    {
        return "";
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return "";
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // We aren't able to retrieve any private credentials
    }
}
