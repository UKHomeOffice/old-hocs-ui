<?php

namespace HomeOffice\SecurityBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CtsCaseVoter implements VoterInterface
{
    const EDIT = 'edit';
    const ALLOCATE = 'allocate';
    const DELETE = 'delete';
    const ASSIGNED_CASE = 'assigned';
 
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::EDIT,
            self::ALLOCATE,
            self::DELETE,
            self::ASSIGNED_CASE
        ));
    }

    public function supportsClass($class)
    {
        $supportedClass = 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase';
        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @var \HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase $ctsCase
     */
    public function vote(TokenInterface $token, $ctsCase, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass(get_class($ctsCase))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }
     
        // check if the voter is used correct, only allow one attribute
        // this isn't a requirement, it's just one easy way for you to
        // design your voter
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed for EDIT / ALLOCATE / DELETE'
            );
        }

        // set the attribute to check against
        $attribute = $attributes[0];

        // check if the given attribute is covered by this voter
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }
     
        switch ($attribute) {
            case self::EDIT:
                // we assume that our data object has a method getOwner() to
                // get the current owner user entity for this data object
                if ($ctsCase->getCanUpdateProperties()) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
            case self::ALLOCATE:
                if ($ctsCase->getCanAssignUser()) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
            case self::DELETE:
                if ($ctsCase->getCanDeleteObject()) {
                    if (!method_exists($ctsCase, 'getGroupedCases')) {
                        return VoterInterface::ACCESS_GRANTED;
                    }
                    if (method_exists($ctsCase, 'getGroupedCases') && count($ctsCase->getGroupedCases()) == 0) {
                        return VoterInterface::ACCESS_GRANTED;
                    }
                }
                break;
            case self::ASSIGNED_CASE:
                if ($ctsCase->getAssignedUser() == $token->getUser()->getUserName()) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
        }
     
        return VoterInterface::ACCESS_DENIED;
    }
}
