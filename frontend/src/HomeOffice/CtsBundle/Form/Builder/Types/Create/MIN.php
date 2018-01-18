<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Create;

use HomeOffice\AlfrescoApiBundle\Service\Channel;
use HomeOffice\AlfrescoApiBundle\Service\MarkupDecisions;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MIN
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Create
 */
class MIN extends AbstractCreateType
{
    // Case Details
    use Elements\DateReceived;
    use Elements\DateOfLetter;
    use Elements\OriginalChannel;
    use Groups\LinkedCase;
    use Elements\Advice;

    // Original Correspondence
    use Groups\Document;

    // Reply To
    use Elements\CorrespondentIsMemberOfParliament;
    use Groups\MemberOfParliament;
    use Elements\ReplyToNumberTenCopy;
    use Elements\HomeSecretaryReply;

    // Correspondent
    use Elements\CorrespondentTitle;
    use Elements\CorrespondentForename;
    use Elements\CorrespondentSurname;
    use Elements\CorrespondentAddressLineOne;
    use Elements\CorrespondentAddressLineTwo;
    use Elements\CorrespondentAddressLineThree;
    use Elements\CorrespondentPostCode;
    use Elements\CorrespondentCountry;
    use Elements\CorrespondentTelephone;
    use Elements\CorrespondentEmail;

    // Tertiary Correspondent
    use Elements\TertiaryCorrespondentTitle;
    use Elements\TertiaryCorrespondentForename;
    use Elements\TertiaryCorrespondentSurname;
    use Elements\TertiaryCorrespondentPostCode;
    use Elements\TertiaryCorrespondentAddressLineOne;
    use Elements\TertiaryCorrespondentAddressLineTwo;
    use Elements\TertiaryCorrespondentAddressLineThree;
    use Elements\TertiaryCorrespondentCountry;
    use Elements\TertiaryCorrespondentTelephone;
    use Elements\TertiaryCorrespondentEmail;

    // Markup
    use Elements\MarkupDecision;
    use Groups\MarkupAllocateToDraft;
    use Elements\MarkupNoReplyNeeded;
    use Elements\MarkupReferToOGD;
    use Elements\Save;

    /**
     * @inheritdoc
     */
    public function buildElements(FormBuilderInterface $builder, array $options)
    {
        $this
            // Case Details
            ->dateReceived($builder)
            ->dateOfLetter($builder)
            ->originalChannel($builder, [Channel::EMAIL, Channel::POST, Channel::PHONE, Channel::NO10])
            ->linkedCaseForm($builder)
            ->advice($builder)

            // Original Correspondence
            ->documentForm($builder)

            // Reply To
            ->correspondentIsMemberOfParliament($builder)
            ->memberOfParliament($builder, $this->getListService())
            ->replyToNumberTenCopy($builder)
            ->homeSecretaryReply($builder)

            // Correspondent
            ->correspondentTitle($builder)
            ->correspondentForename($builder)
            ->correspondentSurname($builder)
            ->correspondentAddressLineOne($builder)
            ->correspondentAddressLineTwo($builder)
            ->correspondentAddressLineThree($builder)
            ->correspondentPostCode($builder)
            ->correspondentCountry($builder)
            ->correspondentTelephone($builder)
            ->correspondentEmail($builder)

            // Third Party
            ->tertiaryCorrespondentTitle($builder)
            ->tertiaryCorrespondentForename($builder)
            ->tertiaryCorrespondentSurname($builder)
            ->tertiaryCorrespondentAddressLineOne($builder)
            ->tertiaryCorrespondentAddressLineTwo($builder)
            ->tertiaryCorrespondentAddressLineThree($builder)
            ->tertiaryCorrespondentPostCode($builder)
            ->tertiaryCorrespondentCountry($builder)
            ->tertiaryCorrespondentTelephone($builder)
            ->tertiaryCorrespondentEmail($builder)
        ;

        if ($this->isEditable($builder->getData())) {
            switch ($builder->getData()->getCaseTask()) {
                case 'Create case':
                    $this
                        ->allocate($builder, $this->getListService())
                        ->save($builder);
                    break;
                case 'QA case':
                    $this->allocate($builder, $this->getListService());
                    break;
                case 'Mark up':
                    $this
                        ->markupDecision($builder, MarkupDecisions::getGuftDecisionList($builder->getData()))
                        ->markupAllocateToDraft(
                            $builder,
                            $this->getListService(),
                            $this->getTopicsService(),
                            ['topics' => true, 'team' => true, 'minister' => true, 'standardLines' => true],
                            false,
                            'Responsible unit'
                        )
                        ->markupNoReplyNeeded($builder)
                        ->markupReferToOGD($builder)
                        ->save($builder);
                    break;
                default:
                    $this->save($builder);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'MINCreate';
    }
}
