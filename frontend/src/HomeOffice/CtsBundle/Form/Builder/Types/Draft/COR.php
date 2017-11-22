<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Draft;

use HomeOffice\AlfrescoApiBundle\Service\HmpoResponse;
use HomeOffice\AlfrescoApiBundle\Service\MarkupDecisions;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class COR
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Draft
 */
abstract class COR extends AbstractDraftType
{
    use Groups\LinkedCase;
    use Elements\MarkupDecision;
    use Elements\MarkupNoReplyNeeded;
    use Elements\MarkupReferToOGD;
    use Groups\MarkupTopics;
    use Elements\HmpoResponse;
    use Elements\ResponsePhoneComment;
    use Groups\Document;
    use Groups\DocumentRemoval;
    use Elements\BringUp;
    use Elements\BringUpDate;
    use Elements\Defer;
    use Elements\DeferDueTo;
    use Groups\Refund;
    use Elements\HmpoComplaintOutcome;
    use Groups\Allocate;
    use Elements\Save;

    /**
     * @inheritdoc
     */
    public function buildElements(FormBuilderInterface $builder, array $options)
    {
        $this
            ->linkedCaseForm($builder)
            ->markupDecision($builder, MarkupDecisions::getGuftDecisionList($builder->getData()))
            ->markupNoReplyNeeded($builder)
            ->markupReferToOGD($builder)
            ->markupTopics($builder, $this->getTopicsService())
            ->hmpoResponse($builder, [HmpoResponse::EMAIL, HmpoResponse::POST, HmpoResponse::PHONE])
            ->documentForm($builder, 'responsePhone')
            ->responsePhoneComment($builder)
            ->documentForm($builder)
            ->documentRemoval($builder)
            ->bringUp($builder)
            ->bringUpDate($builder)
            ->defer($builder)
            ->deferDueTo($builder)
            ->refund($builder)
            ->allocate($builder, $this->getListService())
            ->save($builder)
        ;
    }
}
