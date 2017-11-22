<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Draft;

use HomeOffice\AlfrescoApiBundle\Service\HmpoResponse;
use HomeOffice\AlfrescoApiBundle\Service\MarkupDecisions;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FOI
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Draft
 */
class FOI extends BaseFoi
{
    use Elements\FoiMinisterSignOff;
    use Elements\AnsweringMinister;
    use Elements\FoiIsEir;
    use Groups\PublicInterestTest;
    use Elements\MarkupDecision;
    use Groups\MarkupAllocateToDraft;
    use Elements\MarkupReferToDCU;
    use Elements\MarkupNoReplyNeeded;

    /**
     * @inheritdoc
     */
    public function buildElements(FormBuilderInterface $builder, array $options)
    {
        parent::buildElements($builder, $options);

        $this
            ->foiMinisterSignOff($builder)
            ->answeringMinister(
                $builder,
                $this->getMinisterList(),
                'Sign off Minister',
                'Select Sign off Minister'
            )
            ->foiIsEir($builder)
            ->publicInterestTest($builder)
            ->markupDecision($builder, MarkupDecisions::getGuftDecisionList($builder->getData()))
            ->markupAllocateToDraft(
                $builder,
                $this->getListService(),
                $this->getTopicsService(),
                ['exemptions' => true],
                true
            )
            ->markupReferToDCU($builder)
            ->markupNoReplyNeeded($builder)
        ;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'FOIDraft';
    }
}
