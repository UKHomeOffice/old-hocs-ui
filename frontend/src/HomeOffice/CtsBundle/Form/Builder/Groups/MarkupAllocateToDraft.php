<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\AlfrescoApiBundle\Service\Topic\TopicService;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\ListBundle\Service\ListService;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MarkupAllocateToDraft
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait MarkupAllocateToDraft
{
    use Elements\MarkupUnit;
    use Elements\MarkupMinister;
    use Allocate;
    use MarkupReview;
    use MarkupTopics;
    use Elements\MarkupTeam;
    use Elements\Exemptions;

    /**
     * @param FormBuilderInterface $builder
     * @param ListService          $listService
     * @param TopicService         $topicService
     * @param array                $additionalFields
     * @param bool                 $hideMarkupUnit
     * @param string               $markupUnitLabel
     *
     * @return static
     */
    public function markupAllocateToDraft(
        FormBuilderInterface $builder,
        ListService $listService,
        TopicService $topicService,
        array $additionalFields = [],
        $hideMarkupUnit = false,
        $markupUnitLabel = 'Answering unit'
    ) {
        if ($hideMarkupUnit === false) {
            $this->markupUnit($builder, $listService->getUnitArray(), $markupUnitLabel);
        }

        if (isset($additionalFields['exemptions'])) {
            $this->exemptions($builder);
        }

        $this->allocate($builder, $listService);

        if (isset($additionalFields['review'])) {
            $this->markupReview($builder);
        }

        if (isset($additionalFields['team'])) {
            $this->markupTeam($builder, $listService);
        }

        if (isset($additionalFields['topics'])) {
            $this->markupTopics($builder, $topicService, isset($additionalFields['standardLines']));
        }

        if (isset($additionalFields['minister'])) {
            $this->markupMinister($builder, $listService->getMinisterArray());
        }

        return $this;
    }
}
