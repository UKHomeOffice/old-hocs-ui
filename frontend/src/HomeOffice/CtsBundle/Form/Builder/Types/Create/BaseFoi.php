<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Create;

use HomeOffice\AlfrescoApiBundle\Service\MarkupDecisions;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class BaseFoi
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Create
 */
abstract class BaseFoi extends AbstractCreateType
{
    use Elements\CorrespondenceType;
    use Elements\DateReceived;
    use Elements\HOCaseOfficer;
    use Groups\LinkedCase;
    use Elements\Priority;
    use Groups\Document;
    use Groups\Correspondent;
    use Elements\MarkupDecision;
    use Groups\MarkupAllocateToDraft;
    use Elements\MarkupReferToDCU;
    use Elements\MarkupNoReplyNeeded;
    use Elements\Save;

    /**
     * @inheritdoc
     */
    protected function buildElements(FormBuilderInterface $builder, array $options)
    {
        $this
            ->dateReceived($builder)
            ->hoCaseOfficer($builder)
            ->linkedCaseForm($builder)
            ->priority($builder)
            ->documentForm($builder)
            ->correspondent($builder)
            ->markupDecision($builder, MarkupDecisions::getGuftDecisionList($builder->getData()))
            ->markupAllocateToDraft(
                $builder,
                $this->getListService(),
                $this->getTopicsService(),
                ['topics' => true]
            )
            ->markupReferToDCU($builder)
            ->MarkupNoReplyNeeded($builder)
            ->save($builder)
        ;
    }
}
