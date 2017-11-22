<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Create;

use HomeOffice\AlfrescoApiBundle\Service\Channel;
use HomeOffice\AlfrescoApiBundle\Service\MarkupDecisions;
use HomeOffice\AlfrescoApiBundle\Service\OfficeOfOriginService;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class COR
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Create
 */
class COR extends AbstractCreateType
{
    use Elements\DateReceived;
    use Elements\CaseResponseDeadline;
    use Elements\OriginalChannel;
    use Elements\OfficeOfOrigin;
    use Elements\PassportNumber;
    use Elements\ApplicationNumber;
    use Groups\LinkedCase;
    use Elements\Priority;
    use Groups\Document;
    use Groups\Correspondent;
    use Groups\SecondaryCorrespondent;
    use Groups\TertiaryCorrespondent;
    use ELements\MarkupDecision;
    use Groups\MarkupAllocateToDraft;
    use ELements\MarkupNoReplyNeeded;
    use ELements\MarkupReferToOGD;
    use Elements\Save;

    /**
     * @inheritdoc
     */
    protected function buildElements(FormBuilderInterface $builder, array $options)
    {
        $this
            ->dateReceived($builder)
            ->caseResponseDeadline($builder, 'Final deadline')
            ->originalChannel($builder, [Channel::EMAIL, Channel::POST, Channel::PHONE])
            ->officeOfOrigin($builder, OfficeOfOriginService::getAll(true))
            ->passportNumber($builder)
            ->applicationNumber($builder)
            ->linkedCaseForm($builder)
            ->priority($builder)
            ->documentForm($builder)
            ->correspondent($builder)
            ->secondaryCorrespondent($builder)
            ->tertiaryCorrespondent($builder)
            ->markupDecision($builder, MarkupDecisions::getGuftDecisionList($builder->getData()))
            ->markupAllocateToDraft(
                $builder,
                $this->getListService(),
                $this->getTopicsService(),
                ['topics' => true]
            )
            ->markupNoReplyNeeded($builder)
            ->markupReferToOGD($builder)
            ->save($builder)
        ;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'CORCreate';
    }
}
