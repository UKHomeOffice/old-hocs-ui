<?php

namespace HomeOffice\SecurityBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PersonVoter implements VoterInterface
{
    const CREATE = 'create';
    const MANAGE_TEMPLATES = 'manageTemplates';
    const MANAGE_STANDARD_LINES = 'manageStandardLines';
    const BULK_CREATE_CASES = 'bulkCreateCases';

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::CREATE,
            self::MANAGE_TEMPLATES,
            self::MANAGE_STANDARD_LINES,
            self::BULK_CREATE_CASES
        ));
    }

    public function supportsClass($class)
    {
        $supportedClass = 'HomeOffice\AlfrescoApiBundle\Entity\Person';
        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @var \HomeOffice\AlfrescoApiBundle\Entity\Person $person
     */
    public function vote(TokenInterface $token, $person, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass(get_class($person))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // check if the voter is used correct, only allow one attribute
        // this isn't a requirement, it's just one easy way for you to
        // design your voter
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed for CREATE, MANAGE_TEMPLATES, MANAGE_STANDARD_LINES or BULK_CREATE_CASES'
            );
        }

        // set the attribute to check against
        $attribute = $attributes[0];

        // check if the given attribute is covered by this voter
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }
     
        switch ($attribute) {
            case self::CREATE:
                if ($person->getCasesPermissions() != null &&
                    $person->getCasesPermissions()->getCanCreateFolder() == 'true'
                ) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
            case self::MANAGE_TEMPLATES:
                if ($person->getDocumentTemplatesPermissions() != null &&
                    $person->getDocumentTemplatesPermissions()->getCanCreateFolder() == 'true'
                ) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
            case self::MANAGE_STANDARD_LINES:
                if ($person->getStandardLinesPermissions() != null &&
                    $person->getStandardLinesPermissions()->getCanCreateFolder() == 'true'
                ) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
            case self::BULK_CREATE_CASES:
                if ($person->getAutoCreatePermissions() != null &&
                    $person->getAutoCreatePermissions()->getCanCreateFolder() == 'true'
                ) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
        }
     
        return VoterInterface::ACCESS_DENIED;
    }
}
