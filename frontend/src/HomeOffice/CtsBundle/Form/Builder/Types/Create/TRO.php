<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Create;

use HomeOffice\AlfrescoApiBundle\Service\Channel;
use HomeOffice\AlfrescoApiBundle\Service\MarkupDecisions;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TRO
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Create
 */
class TRO extends AbstractCreateType
{
    // Case details
    use Elements\DateReceived;
    use Elements\DateOfLetter;
    use Elements\OriginalChannel;
    use Groups\LinkedCase;

    // Original correspondence
    use Groups\Document;

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
    use Elements\ReplyToNumberTenCopy;

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
            // Case details
            ->dateReceived($builder)
            ->dateOfLetter($builder)
            ->originalChannel($builder, [Channel::EMAIL, Channel::POST, Channel::PHONE, Channel::NO10])
            ->linkedCaseForm($builder)

            // Original Correspondence
            ->documentForm($builder)

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
            ->replyToNumberTenCopy($builder)
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
                            ['topics' => true, 'standardLines' => true, 'team' => true],
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
     * @return string
     */
    public function getName()
    {
        return 'TROCreate';
    }
}
