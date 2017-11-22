<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Create;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class COL
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Create
 */
class COL extends AbstractCreateType
{
    use Groups\LinkedCase;
    use Elements\DepartureDateFromUK;
    use Elements\HardCopyReceived;
    use Elements\Priority;
    use Elements\CorrespondingName;
    use Elements\NumberOfChildren;
    use Elements\CountryOfDestination;
    use Elements\OtherCountriesToBeVisited;
    use Elements\CountriesToBeTravelledThrough;
    use Elements\ArrivingDateInUK;
    use Elements\IndividualHousehold;
    use Elements\LeadersAddressAboard;
    use Elements\CorrespondentAddressLineOne;
    use Elements\CorrespondentAddressLineTwo;
    use Elements\CorrespondentAddressLineThree;
    use Elements\CorrespondentPostCode;
    use Elements\CorrespondentEmail;
    use Elements\PartyLeaderLastName;
    use Elements\PartyLeaderOtherNames;
    use Elements\PartyLeaderPassportNumber;
    use Elements\PartyLeaderPassportIssuedAt;
    use Elements\PartyLeaderPassportIssuedOn;
    use Elements\PartyLeaderDeputyLastName;
    use Elements\PartyLeaderDeputyOtherNames;
    use Elements\PartyLeaderDeputyPassportNumber;
    use Elements\PartyLeaderDeputyPassportIssuedAt;
    use Elements\PartyLeaderDeputyPassportIssuedOn;
    use Elements\FeeIncluded;
    use Elements\DeliveryType;
    use Elements\Save;

    /**
     * @inheritdoc
     */
    protected function buildElements(FormBuilderInterface $builder, array $options)
    {
        $this
            ->departureDateFromUK($builder)
            ->hardCopyReceived($builder)
            ->linkedCaseForm($builder)
            ->priority($builder)
            ->correspondingName($builder)
            ->numberOfChildren($builder)
            ->countryOfDestination($builder)
            ->otherCountriesToBeVisited($builder)
            ->countriesToBeTravelledThrough($builder)
            ->arrivingDateInUK($builder)
            ->individualHousehold($builder)
            ->leadersAddressAboard($builder)
            ->correspondentAddressLineOne($builder)
            ->correspondentAddressLineTwo($builder)
            ->correspondentAddressLineThree($builder)
            ->correspondentPostCode($builder)
            ->correspondentEmail($builder)
            ->partyLeaderLastName($builder)
            ->partyLeaderOtherNames($builder)
            ->partyLeaderPassportNumber($builder)
            ->partyLeaderPassportIssuedAt($builder)
            ->partyLeaderPassportIssuedOn($builder)
            ->partyLeaderDeputyLastName($builder)
            ->partyLeaderDeputyOtherNames($builder)
            ->partyLeaderDeputyPassportNumber($builder)
            ->partyLeaderDeputyPassportIssuedAt($builder)
            ->partyLeaderDeputyPassportIssuedOn($builder)
            ->feeIncluded($builder)
            ->deliveryType($builder)
            ->save($builder)
            ->save($builder, 'saveAndCreateLetter')
        ;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'COLCreate';
    }
}
