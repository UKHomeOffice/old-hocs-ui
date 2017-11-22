<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\HmpoStandardDetails;
use HomeOffice\AlfrescoApiBundle\Entity\CaseComponents\CorrespondentDetails;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use \DateTime;

/**
 * Class CtsHmpoCollCase
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity\Cases
 *
 * @Assert\Callback(methods={"validateArrivingDateInUk"})
 * @Assert\Callback(methods={"validateDepartureDateFromUk"})
 */
class CtsHmpoCollCase extends CtsCase
{
    use CorrespondentDetails;
    use HmpoStandardDetails;

    private $priority;
    private $hardCopyReceived;
    private $correspondingName;
    private $numberOfChildren;
    private $countryOfDestination = [];
    private $otherCountriesToBeVisited = [];
    private $countriesToBeTravelledThrough = [];
    private $departureDateFromUK;
    private $arrivingDateInUK;
    private $individualHousehold;
    private $feeIncluded;
    private $leadersAddressAboard;
    private $partyLeaderLastName;
    private $partyLeaderOtherNames;
    private $partyLeaderPassportNumber;
    private $partyLeaderPassportIssuedAt;
    private $partyLeaderPassportIssuedOn;
    private $partyLeaderDeputyLastName;
    private $partyLeaderDeputyOtherNames;
    private $partyLeaderDeputyPassportNumber;
    private $partyLeaderDeputyPassportIssuedAt;
    private $partyLeaderDeputyPassportIssuedOn;
    private $deliveryType;
    private $examinerSecurityCheck;
    private $passportStatus;
    private $bringUpDate;
    private $deferDispatch;
    private $dispatchedDate;
    private $deliveryNumber;
    private $amendments;


    /**
     * @return string
     */
    public function getCanonicalCorrespondent()
    {
        return $this->correspondingName;
    }

    /**
     * @return
     */
    public function getHardCopyReceived()
    {
        return $this->hardCopyReceived;
    }

    /**
     * @param mixed $hardCopyReceived
     */
    public function setHardCopyReceived($hardCopyReceived)
    {
        $this->hardCopyReceived =  DateHelper::forceDateTimeOrBlank($hardCopyReceived);
    }

    /**
     * @return mixed
     */
    public function getCorrespondingName()
    {
        return $this->correspondingName;
    }

    /**
     * @return mixed
     */
    public function getPartyName()
    {
        return $this->getCorrespondingName();
    }

    /**
     * @param mixed $correspondingName
     */
    public function setCorrespondingName($correspondingName)
    {
        $this->correspondingName = $correspondingName;
    }

    /**
     * @return mixed
     */
    public function getNumberOfChildren()
    {
        return $this->numberOfChildren;
    }

    /**
     * @param mixed $numberOfChildren
     */
    public function setNumberOfChildren($numberOfChildren)
    {
        $this->numberOfChildren = $numberOfChildren;
    }

    /**
     * @return mixed
     */
    public function getCountryOfDestination()
    {
        return $this->countryOfDestination;
    }

    /**
     * @param bool $includeOtherCountries
     * @param bool $uppercase
     *
     * @return string
     */
    public function getCountryOfDestinationString($includeOtherCountries = false, $uppercase = false)
    {
        if ($includeOtherCountries == true) {
            $countries = array_merge($this->countryOfDestination, $this->otherCountriesToBeVisited);
        } else {
            $countries = $this->countryOfDestination;
        }
        $countries = implode(', ', $countries);

        $pos = strrpos($countries, ',');
        if ($pos !== false) {
            $countries = substr_replace($countries, ' and', $pos, strlen(','));
        }

        return $uppercase ? strtoupper($countries) : $countries;
    }

    /**
     * @param string|array $countryOfDestination
     */
    public function setCountryOfDestination($countryOfDestination)
    {
        if (is_string($countryOfDestination)) {
            $countryOfDestination = explode(',', $countryOfDestination);
        }

        $this->countryOfDestination = $countryOfDestination;
    }

    /**
     * @return array
     */
    public function getOtherCountriesToBeVisited()
    {
        return $this->otherCountriesToBeVisited;
    }

    /**
     * @param string|array $otherCountriesToBeVisited
     */
    public function setOtherCountriesToBeVisited($otherCountriesToBeVisited)
    {
        if (is_string($otherCountriesToBeVisited)) {
            $otherCountriesToBeVisited = explode(',', $otherCountriesToBeVisited);
        }

        $this->otherCountriesToBeVisited = $otherCountriesToBeVisited;
    }

    /**
     * @return array
     */
    public function getCountriesToBeTravelledThrough()
    {
        return $this->countriesToBeTravelledThrough;
    }

    /**
     * @return string
     */
    public function getCountriesToBeTravelledThroughString()
    {
        $countries = implode(', ', $this->countriesToBeTravelledThrough);

        $pos = strrpos($countries, ',');
        if ($pos !== false) {
            $countries = substr_replace($countries, ' and', $pos, strlen(','));
        }

        return $countries;
    }

    /**
     * @param string|array $countriesToBeTravelledThrough
     */
    public function setCountriesToBeTravelledThrough($countriesToBeTravelledThrough)
    {
        if (is_string($countriesToBeTravelledThrough)) {
            $countriesToBeTravelledThrough = explode(',', $countriesToBeTravelledThrough);
        }

        $this->countriesToBeTravelledThrough = $countriesToBeTravelledThrough;
    }

    /**
     * @return \DateTime
     */
    public function getDepartureDateFromUK()
    {
        return $this->departureDateFromUK;
    }

    /**
     * @param mixed $departureDateFromUK
     */
    public function setDepartureDateFromUK($departureDateFromUK)
    {
        $this->departureDateFromUK = DateHelper::forceDateTimeOrBlank($departureDateFromUK);
    }

    /**
     * @return mixed
     */
    public function getArrivingDateInUK()
    {
        return $this->arrivingDateInUK;
    }

    /**
     * @param mixed $arrivingDateInUK
     */
    public function setArrivingDateInUK($arrivingDateInUK)
    {
        $this->arrivingDateInUK = DateHelper::forceDateTimeOrBlank($arrivingDateInUK);
    }

    /**
     * @return mixed
     */
    public function getIndividualHousehold()
    {
        return $this->individualHousehold;
    }

    /**
     * @param mixed $individualHousehold
     */
    public function setIndividualHousehold($individualHousehold)
    {
        $this->individualHousehold = $individualHousehold;
    }


    /**
     * @return mixed
     */
    public function getFeeIncluded()
    {
        return $this->feeIncluded;
    }

    /**
     * @param mixed $feeIncluded
     */
    public function setFeeIncluded($feeIncluded)
    {
        $this->feeIncluded = $feeIncluded;
    }

    /**
     * @return mixed
     */
    public function getLeadersAddressAboard()
    {
        return $this->leadersAddressAboard;
    }

    /**
     * @param mixed $leadersAddressAboard
     */
    public function setLeadersAddressAboard($leadersAddressAboard)
    {
        $this->leadersAddressAboard = $leadersAddressAboard;
    }

    /**
     * @return mixed
     */
    public function getPartyLeaderLastName()
    {
        return $this->partyLeaderLastName;
    }

    /**
     * @param mixed $partyLeaderLastName
     */
    public function setPartyLeaderLastName($partyLeaderLastName)
    {
        $this->partyLeaderLastName = $partyLeaderLastName;
    }

    /**
     * @return mixed
     */
    public function getPartyLeaderOtherNames()
    {
        return $this->partyLeaderOtherNames;
    }

    /**
     * @param mixed $partyLeaderOtherNames
     */
    public function setPartyLeaderOtherNames($partyLeaderOtherNames)
    {
        $this->partyLeaderOtherNames = $partyLeaderOtherNames;
    }

    /**
     * @return string
     */
    public function getPartyLeaderName()
    {
        return $this->getPartyLeaderOtherNames().' '.$this->getPartyLeaderLastName();
    }


    /**
     * @return mixed
     */
    public function getPartyLeaderPassportNumber()
    {
        return $this->partyLeaderPassportNumber;
    }

    /**
     * @param mixed $partyLeaderPassportNumber
     */
    public function setPartyLeaderPassportNumber($partyLeaderPassportNumber)
    {
        $this->partyLeaderPassportNumber = $partyLeaderPassportNumber;
    }

    /**
     * @return mixed
     */
    public function getPartyLeaderPassportIssuedAt()
    {
        return $this->partyLeaderPassportIssuedAt;
    }

    /**
     * @param mixed $partyLeaderPassportIssuedAt
     */
    public function setPartyLeaderPassportIssuedAt($partyLeaderPassportIssuedAt)
    {
        $this->partyLeaderPassportIssuedAt = $partyLeaderPassportIssuedAt;
    }

    /**
     * @return mixed
     */
    public function getPartyLeaderPassportIssuedOn()
    {
        return $this->partyLeaderPassportIssuedOn;
    }

    /**
     * @param mixed $partyLeaderPassportIssuedOn
     */
    public function setPartyLeaderPassportIssuedOn($partyLeaderPassportIssuedOn)
    {
        $this->partyLeaderPassportIssuedOn = DateHelper::forceDateTimeOrBlank($partyLeaderPassportIssuedOn);
    }

    /**
     * @return mixed
     */
    public function getPartyLeaderDeputyLastName()
    {
        return $this->partyLeaderDeputyLastName;
    }

    /**
     * @param mixed $partyLeaderDeputyLastName
     */
    public function setPartyLeaderDeputyLastName($partyLeaderDeputyLastName)
    {
        $this->partyLeaderDeputyLastName = $partyLeaderDeputyLastName;
    }

    /**
     * @return mixed
     */
    public function getPartyLeaderDeputyOtherNames()
    {
        return $this->partyLeaderDeputyOtherNames;
    }

    /**
     * @param mixed $partyLeaderDeputyOtherNames
     */
    public function setPartyLeaderDeputyOtherNames($partyLeaderDeputyOtherNames)
    {
        $this->partyLeaderDeputyOtherNames = $partyLeaderDeputyOtherNames;
    }

    /**
     * @return mixed
     */
    public function getPartyLeaderDeputyPassportNumber()
    {
        return $this->partyLeaderDeputyPassportNumber;
    }

    /**
     * @param mixed $partyLeaderDeputyPassportNumber
     */
    public function setPartyLeaderDeputyPassportNumber($partyLeaderDeputyPassportNumber)
    {
        $this->partyLeaderDeputyPassportNumber = $partyLeaderDeputyPassportNumber;
    }

    /**
     * @return mixed
     */
    public function getPartyLeaderDeputyPassportIssuedAt()
    {
        return $this->partyLeaderDeputyPassportIssuedAt;
    }

    /**
     * @param mixed $partyLeaderDeputyPassportIssuedAt
     */
    public function setPartyLeaderDeputyPassportIssuedAt($partyLeaderDeputyPassportIssuedAt)
    {
        $this->partyLeaderDeputyPassportIssuedAt = $partyLeaderDeputyPassportIssuedAt;
    }

    /**
     * @return mixed
     */
    public function getPartyLeaderDeputyPassportIssuedOn()
    {
        return $this->partyLeaderDeputyPassportIssuedOn;
    }

    /**
     * @param mixed $partyLeaderDeputyPassportIssuedOn
     */
    public function setPartyLeaderDeputyPassportIssuedOn($partyLeaderDeputyPassportIssuedOn)
    {
        $this->partyLeaderDeputyPassportIssuedOn = DateHelper::forceDateTimeOrBlank($partyLeaderDeputyPassportIssuedOn);
    }

    /**
     * @return mixed
     */
    public function getDeliveryType()
    {
        return $this->deliveryType;
    }

    /**
     * @param mixed $deliveryType
     */
    public function setDeliveryType($deliveryType)
    {
        $this->deliveryType = $deliveryType;
    }

    /**
     * @return mixed
     */
    public function getExaminerSecurityCheck()
    {
        return $this->examinerSecurityCheck == "true" ? true : false;
    }

    /**
     * @param mixed $examinerSecurityCheck
     */
    public function setExaminerSecurityCheck($examinerSecurityCheck)
    {
        $this->examinerSecurityCheck = $examinerSecurityCheck;
    }

    /**
     * @return mixed
     */
    public function getPassportStatus()
    {
        return $this->passportStatus;
    }

    /**
     * @param mixed $passportStatus
     */
    public function setPassportStatus($passportStatus)
    {
        $this->passportStatus = $passportStatus;
    }

    /**
     * @return mixed
     */
    public function getBringUpDate()
    {
        return $this->bringUpDate;
    }

    /**
     * @param mixed $bringUpDate
     */
    public function setBringUpDate($bringUpDate)
    {
        $this->bringUpDate = DateHelper::forceDateTimeOrBlank($bringUpDate);
    }

    /**
     * @return mixed
     */
    public function getDeferDispatch()
    {
        return $this->deferDispatch;
    }

    /**
     * @param mixed $deferDispatch
     */
    public function setDeferDispatch($deferDispatch)
    {
        $this->deferDispatch = $deferDispatch;
    }

    /**
     * @return mixed
     */
    public function getDispatchedDate()
    {
        return $this->dispatchedDate;
    }

    /**
     * @param mixed $dispatchedDate
     */
    public function setDispatchedDate($dispatchedDate)
    {
        $this->dispatchedDate = DateHelper::forceDateTimeOrBlank($dispatchedDate);
    }

    /**
     * @return mixed
     */
    public function getDeliveryNumber()
    {
        return $this->deliveryNumber;
    }

    /**
     * @param mixed $deliveryNumber
     */
    public function setDeliveryNumber($deliveryNumber)
    {
        $this->deliveryNumber = $deliveryNumber;
    }

    /**
     * @return boolean
     */
    public function getPriority()
    {
        return $this->priority == "true" ? true : false;
    }

    /**
     * @param string $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return bool
     */
    public function getAmendments()
    {
        return false;
    }

    /**
     * @param $amendments
     */
    public function setAmendments($amendments)
    {
        $this->amendments = $amendments;
    }

    /**
     * @return string
     */
    public function getPassportNumber()
    {
        preg_match('/([0-9]{6})\//', $this->getUrnSuffix(), $matches);

        return array_key_exists(1, $matches) ? $matches[1] : '';
    }

    /**
     * Validate Arrival Date
     *
     * @param ExecutionContextInterface $context
     */
    public function validateArrivingDateInUk(ExecutionContextInterface $context)
    {
        if($this->arrivingDateInUK !== null) {
            $today = new DateTime('today');

            if ($this->arrivingDateInUK < $today) {
                $context->buildViolation('The arrival date cannot be in the past')
                    ->atPath('arrivingDateInUk')
                    ->addViolation();
            }
        }
    }

    /**
     * Validate Arrival Date
     *
     * @param ExecutionContextInterface $context
     */
    public function validateDepartureDateFromUk(ExecutionContextInterface $context)
    {
        if($this->departureDateFromUK !== null) {
            $today = new DateTime('today');

            if ($this->departureDateFromUK < $today) {
                $context->buildViolation('The departure date cannot be in the past')
                    ->atPath('departureDateFromUk')
                    ->addViolation();
            }
        }
    }
}
