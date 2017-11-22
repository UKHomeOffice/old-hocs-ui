<?php

namespace HomeOffice\AlfrescoApiBundle\Service;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\Person;
use HomeOffice\AlfrescoApiBundle\Entity\Team;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\ListBundle\Service\ListHandler;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class CTSHelper
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
class CTSHelper
{
    /**
     * @var SecurityContext
     */
    private $securityContext;

    /**
     * @var ListHandler
     */
    private $listHandler;

    /**
     * @param $securityContext
     * @param $listHandler
     */
    public function __construct($securityContext, $listHandler)
    {
        $this->securityContext = $securityContext;
        $this->listHandler     = $listHandler;
    }

    /**
     * Get ListHandler
     *
     * @return ListHandler
     */
    public function getListHandler()
    {
        return $this->listHandler;
    }

    /**
     * Format a date string into d/m/y format
     * @param string $date
     * @return string
     */
    public function formatAtomStringToDate($date)
    {
        $dateTime = DateHelper::fromStringOrBlank($date);
        return $dateTime ? $dateTime->toUkDate(false, false) : '';
    }

    /**
     * Format a datetime string into d/m/y h:i:s format
     * @param string $date
     * @return string
     */
    public function formatAtomStringToDateTime($date)
    {
        $dateTime = DateHelper::fromStringOrBlank($date);
        return $dateTime ? $dateTime->toUkDate(true, false) : '';
    }

    /**
     * Return the formatted string for a DateTime object.
     * @param mixed $date
     * @return string
     */
    public function formatDateTimeToDate($date)
    {
        $dateTime = DateHelper::fromNativeOrNull($date);
        return $dateTime ? $dateTime->toUkDate() : '';
    }

    /**
     * Return the formatted string for a DateTime object.
     * @param mixed     $date
     * @param boolean   $suppressSeconds
     * @param boolean   $linebreakTime
     * @return string
     */
    public function formatDateTimeToDateTime($date, $suppressSeconds = false, $linebreakTime = false)
    {
        $dateTime = DateHelper::fromNativeOrNull($date);

        if ($dateTime != '') {
            $d   = $dateTime->toUkDate();
            $t   = $suppressSeconds ? $date->format('H:i') : $date->format('H:i:s');
            $pad = $linebreakTime ? '<br>' : ' ';
            return $d . $pad . $t;
        }

        return '';
    }

    /**
     * Format the date as a ISO 8601 date and return
     * @param  mixed  $date
     * @return string
     */
    public function formatDateToIsoDate($date)
    {
        $dateTime = DateHelper::fromNativeOrNull($date);
        if ($dateTime != '') {
            return $dateTime->toIso();
        }

        return '';
    }

    /**
     * Format the date as a ISO 8601 date with milliseconds and return
     * @param  mixed  $date
     * @return string
     */
    public function formatDateToIsoDateWithMilliseconds($date)
    {
        $dateTime = DateHelper::fromNativeOrNull($date);
        if ($dateTime != '') {
            return $dateTime->toIso(true);
        }

        return '';
    }

    /**
     * Format the date as a ISO 8601 date and return
     * @param  mixed  $date
     * @return string
     */
    public function formatDateToIsoToEndOfDayDate($date)
    {
        $dateTime = DateHelper::fromNativeOrNull($date);

        if ($dateTime !== null) {
            return $dateTime->tomorrow()->midnight()->toIso();
        }

        return '';
    }

    /**
     * @param boolean $boolean
     * @return string
     */
    public function formatBooleanToYesOrNo($boolean)
    {
        return $boolean ? 'Yes' : 'No';
    }

    /**
     * @param boolean $boolean
     * @return string
     */
    public function formatBooleanForAtom($boolean)
    {
        return $boolean ? 'true' : 'false';
    }

    /**
     * Get the user object of the currently logged in user
     * @return Person|null
     */
    public function getLoggedInUser()
    {
        /** @var TokenInterface $token */
        $token = $this->securityContext->getToken();

        if ($token instanceof AnonymousToken) {
            return null;
        }

        return (null !== $token)
            ? $token->getUser()
            : null;
    }

    /**
     * Get the username of the currently logged in user
     * @return string
     */
    public function getLoggedInUserName()
    {
        return $this->getLoggedInUser() != null ? $this->getLoggedInUser()->getUserName() : null;
    }

    /**
     * Make a link clickable in a text area
     * @param string $text
     * @return string
     */
    public function makeLink($text)
    {
        preg_match_all('!((https://|http://)[a-z0-9_./?=&-]+)!i', $text, $matchedLinks);
        foreach ($matchedLinks[1] as $link) {
            $originalLink = $link;
            $strippedLink = $this->nicetrim(strip_tags($link));
            $link         = preg_replace(
                '!((https://|http://)[a-z0-9_./?=&-]+)!i',
                "<a href=$link>$1</a>",
                $strippedLink
            );
            $text         = str_replace($originalLink, $link, $text);
        }
        return $text;
    }

    /*
     * Limit the length of the given string to $MAX_LENGTH char
     * If it is more, it keeps the first $MAX_LENGTH-3 characters
     * and adds "..."
     * It counts HTML char such as &aacute; as 1 char.
     * @param string $text
     * @return string
     */
    private function nicetrim($s)
    {
        $MAX_LENGTH   = 60;
        $str_to_count = html_entity_decode($s);
        if (strlen($str_to_count) <= $MAX_LENGTH) {
            return $s;
        }
        $s2 = substr($str_to_count, 0, $MAX_LENGTH - 3);
        $s2 .= "...";
        return htmlentities($s2);
    }

    /**
     *
     * @param string $text
     * @param int    $width
     * @return string
     */
    public function wordWrap($text, $width)
    {
        return wordwrap($text, $width, "\n", true);
    }

    /**
     * Get the high level group for the correspondence type.
     * @param string $correspondenceType
     * @return string
     */
    public function getCorrespondenceTypeGroup($correspondenceType)
    {
        return TypeClassMaps::getSupertype($correspondenceType);
    }

    /**
     * Get the class type required from the correspondence type.
     * @param string $correspondenceType
     * @return string
     */
    public function getCaseClassFromType($correspondenceType)
    {
        return TypeClassMaps::getEntityClass($correspondenceType);
    }

    /**
     * Return the form type class required for the given correspondence type.
     * @param string $correspondenceType
     * @return string
     */
    public function getFormTypeClassFromType($correspondenceType)
    {
        return TypeClassMaps::getFormClass($correspondenceType);
    }

    /**
     * Return array of all search super types from the array of case types
     * passed in.
     * @param array  $correspondenceTypeArray
     * @return array
     */
    public function getSearchSuperTypes(array $correspondenceTypeArray)
    {
        $superTypes = [];

        foreach ($correspondenceTypeArray as $type) {
            $superType              = TypeClassMaps::getSearchSupertype($type);
            $superTypes[$superType] = $superType;
        }

        return $superTypes;
    }

    /**
     *
     * @param array   $unitTeamObjectArray
     * @return string
     */
    public function getUserUnitsTeamsForDisplay(array $unitTeamObjectArray)
    {
        $unitTeamStringArray = array();

        /** @var Unit $unit */
        foreach ($unitTeamObjectArray as $unit) {
            array_push($unitTeamStringArray, $unit->getDisplayName());
        }

        if (empty($unitTeamStringArray)) {
            return 'None';
        }

        return implode(', ', $unitTeamStringArray);
    }

    /**
     * Create a subset of the topic list by the unit selected.
     * @param array  $topicList
     * @return array
     */
    public function handleTopicList($topicList)
    {
        // show all topics and use JS progressive enhancement to filter them
        // down to the ones for the selected unit.
        $allTopics = array();

        foreach ($topicList as $unit) {
            foreach ($unit as $unitTopic) {
                $allTopics[$unitTopic] = $unitTopic;
            }
        }

        return $allTopics;
    }

    /**
     * Add the value to the end of the array if it does not already exist.
     * @param array  $array
     * @param string $value
     * @return array
     */
    public function handleLegacyValue($array, $value)
    {
        if (false === empty($value) && !array_key_exists($value, $array)) {
            $array[$value] = $value;
        }

        return $array;
    }

    /**
     *
     * @param array  $correspondenceTypes
     * @return array
     */
    public function getCorrespondenceTypeGroups(array $correspondenceTypes)
    {
        $typeGroups = array();

        foreach ($correspondenceTypes as $type) {
            $typeGroup              = $this->getCorrespondenceTypeGroup($type);
            $typeGroups[$typeGroup] = $typeGroup;
        }

        return $typeGroups;

    }

    /**
     *
     * @param string $group
     * @return array
     */
    public function getGroupCaseTypes($group)
    {
        return GroupTypeMaps::getGroupCaseTypes($group);
    }

    /**
     * Flatten unit and team object into 1-d list
     * @param $unitAndTeamList
     * @return array
     */
    public function makeFlatMap($unitAndTeamList)
    {
        $flatList = array();

        /** @var Unit $unit */
        foreach ($unitAndTeamList as $unit) {
            $flatList[$unit->getAuthorityName()] = $unit->getDisplayName();

            /** @var Team $team */
            foreach ($unit->getTeams() as $team) {
                $flatList[$team->getAuthorityName()] = $team->getDisplayName();
            }
        }
        return $flatList;
    }

    /**
     * Set the owner property on the case passed in.
     * @param CtsCase $ctsCase
     */
    public function setCaseOwner(CtsCase $ctsCase)
    {
        $caseOwner = $ctsCase->getAssignedUser();

        if (!$caseOwner) {
            $caseOwner = $ctsCase->getAssignedTeam();

            if (!$caseOwner) {
                $caseOwner = $ctsCase->getAssignedUnit();
            }

            $unitAndTeamList = $this->makeFlatMap($this->listHandler->getList('ctsUnitAndTeamList'));

            if ($caseOwner != null && isset($unitAndTeamList[$caseOwner])) {
                // replace team or unit code with name
                $caseOwner = $unitAndTeamList[$caseOwner];
            }
        }

        $ctsCase->setCaseOwner(preg_replace('/^GROUP\_/', '', $caseOwner));
    }

    public function getPQHouseFromUIN($uin)
    {
        if (0 === strpos($uin, 'HL')) {
            return PQHouse::HOUSE_OF_LORDS;
        } else {
            return PQHouse::HOUSE_OF_COMMONS;
        }
    }

    /**
     * Is the user in a parent unit.
     * @return boolean
     */
    public function isUserInParentUnit()
    {
        $parentUnits = GroupTypeMaps::getGroupsArray();

        /** @var Unit $unit */
        foreach ($this->getLoggedInUser()->getUnits() as $unit) {
            if (in_array($unit->getAuthorityName(), $parentUnits)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param CtsCase $case
     *
     * @return string
     */
    public function getRequiredTypeProperty(CtsCase $case)
    {
        return 'required_for_' . $this->getCorrespondenceTypeGroup($case->getCorrespondenceType());
    }
}
